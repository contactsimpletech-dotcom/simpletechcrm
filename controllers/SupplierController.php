<?php
class SupplierController {

    // ── Public: show registration form ────────────────────────────────────────
    public function showRegister(): void {
        View::render('supplier/register', [], true);
    }

    // ── Public: process registration submission ───────────────────────────────
    public function register(): void {
        Csrf::check();

        $data = [
            'company_name'     => trim(Request::str('company_name')),
            'contact_name'     => trim(Request::str('contact_name')),
            'email'            => Request::email('email') ?? '',
            'phone'            => trim(Request::str('phone')),
            'business_type'    => trim(Request::str('business_type')),
            'website'          => trim(Request::str('website')),
            'address'          => trim(Request::str('address')),
            'city'             => trim(Request::str('city')),
            'state'            => trim(Request::str('state')),
            'zip'              => trim(Request::str('zip')),
            'tax_id'           => trim(Request::str('tax_id')),
            'services_offered' => trim(Request::str('services_offered')),
            'notes'            => trim(Request::str('notes')),
            'ip_address'       => $_SERVER['HTTP_CF_CONNECTING_IP']
                                  ?? $_SERVER['HTTP_X_FORWARDED_FOR']
                                  ?? $_SERVER['REMOTE_ADDR']
                                  ?? '',
        ];

        // Basic validation
        if (!$data['company_name'] || !$data['contact_name'] || !$data['email']) {
            View::flash('error', 'Company name, contact name, and email are required.');
            Router::redirect('/admin/supplier/corporate/profile');
        }

        // Check for duplicate pending/approved application from the same email
        $existing = DB::one(
            "SELECT id FROM bh_supplier_applications WHERE email = ? AND status IN ('pending','approved')",
            [$data['email']]
        );
        if ($existing) {
            View::flash('info', 'An application for this email address is already on file. We will be in touch soon.');
            Router::redirect('/admin/supplier/corporate/profile');
        }

        DB::query(
            'INSERT INTO bh_supplier_applications
             (company_name, contact_name, email, phone, business_type, website,
              address, city, state, zip, tax_id, services_offered, notes, ip_address)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
            array_values($data)
        );
        $applicationId = DB::lastId();

        // Notify admin
        self::notifyAdmin($data, $applicationId);

        View::flash('success', 'Thank you! Your application has been submitted. We will review it and contact you shortly.');
        Router::redirect('/admin/supplier/corporate/profile');
    }

    // ── Admin: list all applications ──────────────────────────────────────────
    public function applications(): void {
        Auth::requireAdmin();
        $status = Request::get('status', 'pending');
        $valid  = ['pending', 'approved', 'rejected', 'all'];
        if (!in_array($status, $valid, true)) $status = 'pending';

        $where = $status === 'all' ? '1' : 'status = ?';
        $args  = $status === 'all' ? [] : [$status];
        $apps  = DB::all("SELECT * FROM bh_supplier_applications WHERE {$where} ORDER BY created_at DESC", $args);

        $counts = [];
        foreach (['pending','approved','rejected'] as $s) {
            $row = DB::one("SELECT COUNT(*) AS n FROM bh_supplier_applications WHERE status = ?", [$s]);
            $counts[$s] = $row->n ?? 0;
        }

        View::render('supplier/applications', compact('apps', 'status', 'counts'));
    }

    // ── Admin: update application status ──────────────────────────────────────
    public function updateStatus(): void {
        Auth::requireAdmin();
        Csrf::check();
        $id         = (int) Request::str('id');
        $status     = Request::str('status');
        $adminNotes = trim(Request::str('admin_notes'));
        $valid      = ['pending', 'approved', 'rejected'];
        if (!$id || !in_array($status, $valid, true)) {
            Router::redirect('/admin/supplier/applications');
        }
        DB::update('bh_supplier_applications', ['status' => $status, 'admin_notes' => $adminNotes], ['id' => $id]);
        View::flash('success', 'Application status updated.');
        Router::redirect('/admin/supplier/applications');
    }

    // ── Email admin on new application ────────────────────────────────────────
    private static function notifyAdmin(array $d, int $id): void {
        $to = bh_setting('company_email', '') ?: bh_setting('smtp_from_email', '')
              ?: (defined('BH_MAIL_FROM') ? BH_MAIL_FROM : '');
        if (!$to) return;

        $appUrl  = BH_APP_URL . '/admin/supplier/applications';
        $subject = '[' . BH_APP_NAME . '] New Supplier Application — ' . htmlspecialchars($d['company_name']);
        $rows    = [
            ['Company',      $d['company_name']],
            ['Contact',      $d['contact_name']],
            ['Email',        $d['email']],
            ['Phone',        $d['phone']],
            ['Business Type',$d['business_type']],
            ['Website',      $d['website']],
            ['Address',      trim($d['address'] . ' ' . $d['city'] . ' ' . $d['state'] . ' ' . $d['zip'])],
            ['Tax ID',       $d['tax_id']],
            ['Services',     $d['services_offered']],
            ['Notes',        $d['notes']],
            ['IP',           $d['ip_address']],
            ['Submitted',    date('Y-m-d H:i:s T')],
        ];

        $tableRows = '';
        foreach ($rows as [$label, $val]) {
            if ((string)$val === '') continue;
            $tableRows .= '<tr><td style="padding:8px 12px;font-weight:600;width:130px;white-space:nowrap;">'
                        . htmlspecialchars($label)
                        . '</td><td style="padding:8px 12px;">'
                        . nl2br(htmlspecialchars((string)$val))
                        . '</td></tr>';
        }

        $html = '<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;padding:24px;color:#111;">
<h2 style="color:#2563eb;margin-bottom:4px;">📋 New Supplier Application</h2>
<p style="color:#6b7280;margin-bottom:20px;">Application #' . $id . ' — action required</p>
<table style="border-collapse:collapse;width:100%;max-width:580px;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">'
. $tableRows .
'</table>
<p style="margin-top:24px;">
  <a href="' . htmlspecialchars($appUrl) . '"
     style="background:#2563eb;color:#fff;padding:12px 24px;text-decoration:none;border-radius:6px;display:inline-block;font-weight:600;">
     Review Application
  </a>
</p>
<p style="margin-top:32px;font-size:12px;color:#9ca3af;">Sent by ' . BH_APP_NAME . ' — supplier registration</p>
</body></html>';

        try {
            BHMailer::send($to, $subject, $html);
        } catch (\Throwable $e) {
            error_log('[supplier] Admin notification email failed: ' . $e->getMessage());
        }
    }
}
