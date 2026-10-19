<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Supplier Applications &mdash; <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo BH_ASSETS_URL; ?>/css/app.css">
<style>
.app-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:600;}
.badge-pending{background:#fef3c7;color:#92400e;}
.badge-approved{background:#d1fae5;color:#065f46;}
.badge-rejected{background:#fee2e2;color:#991b1b;}
.stat-row{display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap;}
.stat{background:#fff;border:1.5px solid #e5e7eb;border-radius:10px;padding:14px 20px;min-width:130px;text-align:center;}
.stat .n{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#111827;}
.stat .l{font-size:12px;color:#6b7280;margin-top:2px;}
.filter-row{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;}
.filter-btn{padding:7px 16px;border-radius:7px;border:1.5px solid #d1d5db;background:#fff;font-size:13px;font-weight:600;color:#374151;cursor:pointer;text-decoration:none;}
.filter-btn.active,.filter-btn:hover{border-color:#2563eb;background:#eff6ff;color:#1e40af;}
table{width:100%;border-collapse:collapse;}
th{text-align:left;padding:10px 14px;font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#6b7280;border-bottom:2px solid #e5e7eb;}
td{padding:12px 14px;border-bottom:1px solid #f3f4f6;font-size:13.5px;vertical-align:top;}
tr:hover td{background:#f9fafb;}
.detail-toggle{color:#2563eb;font-size:12px;cursor:pointer;background:none;border:none;padding:0;font-family:inherit;}
.detail-row td{background:#f9fafb;padding:14px 20px;}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 20px;font-size:13px;}
.detail-grid dt{font-weight:600;color:#6b7280;font-size:11.5px;text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px;}
.detail-grid dd{color:#111827;margin:0;}
.action-form{display:inline;}
.action-select{padding:5px 10px;border-radius:6px;border:1.5px solid #d1d5db;font-size:12.5px;font-family:inherit;}
.action-btn{padding:5px 12px;border:none;border-radius:6px;background:#2563eb;color:#fff;font-size:12.5px;font-weight:600;cursor:pointer;font-family:inherit;}
.action-btn:hover{background:#1d4ed8;}
.empty{text-align:center;padding:48px 20px;color:#9ca3af;font-size:14px;}
.alert{padding:11px 14px;border-radius:8px;font-size:13.5px;margin-bottom:20px;display:flex;align-items:flex-start;gap:8px;border:1.5px solid;}
.alert-success{background:#f0fdf4;border-color:#bbf7d0;color:#166534;}
.alert-error{background:#fef2f2;border-color:#fecaca;color:#991b1b;}
.alert-info{background:#eff6ff;border-color:#bfdbfe;color:#1e40af;}
</style>
</head>
<body>
<?php include BH_VIEWS . '/layout/header.php'; ?>

<div style="max-width:1100px;margin:0 auto;padding:32px 20px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#111827;">Supplier Applications</h1>
            <p style="font-size:13px;color:#6b7280;margin-top:3px;">Review and manage corporate supplier registrations</p>
        </div>
        <a href="<?= BH_APP_URL ?>/admin/supplier/corporate/profile" target="_blank"
           style="padding:9px 18px;background:#f3f4f6;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;">
            <i class="fa-solid fa-arrow-up-right-from-square" style="margin-right:6px;"></i>View Form
        </a>
    </div>

    <?php foreach ($flash ?? [] as $f): ?>
    <div class="alert alert-<?php echo View::e($f['type']); ?>">
        <?php if ($f['type']==='success'): ?><i class="fa-solid fa-circle-check"></i>
        <?php elseif ($f['type']==='error'): ?><i class="fa-solid fa-circle-exclamation"></i>
        <?php else: ?><i class="fa-solid fa-circle-info"></i><?php endif; ?>
        <span><?php echo View::e($f['message']); ?></span>
    </div>
    <?php endforeach; ?>

    <div class="stat-row">
        <div class="stat">
            <div class="n" style="color:#92400e;"><?php echo (int)($counts['pending'] ?? 0); ?></div>
            <div class="l">Pending</div>
        </div>
        <div class="stat">
            <div class="n" style="color:#065f46;"><?php echo (int)($counts['approved'] ?? 0); ?></div>
            <div class="l">Approved</div>
        </div>
        <div class="stat">
            <div class="n" style="color:#991b1b;"><?php echo (int)($counts['rejected'] ?? 0); ?></div>
            <div class="l">Rejected</div>
        </div>
    </div>

    <div class="filter-row">
        <?php foreach (['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','all'=>'All'] as $s => $label): ?>
        <a href="?status=<?= $s ?>" class="filter-btn <?= $status === $s ? 'active' : '' ?>"><?= $label ?></a>
        <?php endforeach; ?>
    </div>

    <div style="background:#fff;border:1.5px solid #e5e7eb;border-radius:12px;overflow:hidden;">
    <?php if (empty($apps)): ?>
        <div class="empty">
            <i class="fa-solid fa-inbox" style="font-size:28px;margin-bottom:10px;display:block;"></i>
            No <?= $status === 'all' ? '' : $status ?> applications found.
        </div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Company</th>
                <th>Contact</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($apps as $a): ?>
        <tr>
            <td style="color:#9ca3af;font-size:12px;"><?= (int)$a->id ?></td>
            <td>
                <strong><?php echo View::e($a->company_name); ?></strong>
                <?php if ($a->business_type): ?>
                <div style="font-size:11.5px;color:#6b7280;"><?php echo View::e($a->business_type); ?></div>
                <?php endif; ?>
                <button class="detail-toggle" onclick="toggleDetail(<?= (int)$a->id ?>)" style="margin-top:4px;">
                    <i class="fa-solid fa-chevron-down" id="icon-<?= (int)$a->id ?>"></i> Details
                </button>
            </td>
            <td>
                <?php echo View::e($a->contact_name); ?><br>
                <a href="mailto:<?php echo View::e($a->email); ?>" style="color:#2563eb;font-size:12.5px;"><?php echo View::e($a->email); ?></a>
                <?php if ($a->phone): ?>
                <div style="font-size:12px;color:#6b7280;"><?php echo View::e($a->phone); ?></div>
                <?php endif; ?>
            </td>
            <td style="font-size:12.5px;color:#6b7280;white-space:nowrap;"><?php echo date('M j, Y', strtotime($a->created_at)); ?></td>
            <td>
                <span class="app-badge badge-<?php echo View::e($a->status); ?>">
                    <?php if ($a->status==='pending'): ?><i class="fa-solid fa-clock"></i>
                    <?php elseif ($a->status==='approved'): ?><i class="fa-solid fa-check"></i>
                    <?php else: ?><i class="fa-solid fa-xmark"></i><?php endif; ?>
                    <?php echo ucfirst(View::e($a->status)); ?>
                </span>
            </td>
            <td>
                <form method="POST" action="<?= BH_APP_URL ?>/admin/supplier/status" class="action-form">
                    <?php echo Csrf::field(); ?>
                    <input type="hidden" name="id" value="<?= (int)$a->id ?>">
                    <select name="status" class="action-select">
                        <option value="pending"  <?= $a->status==='pending'  ?'selected':'' ?>>Pending</option>
                        <option value="approved" <?= $a->status==='approved' ?'selected':'' ?>>Approved</option>
                        <option value="rejected" <?= $a->status==='rejected' ?'selected':'' ?>>Rejected</option>
                    </select>
                    <button type="submit" class="action-btn">Save</button>
                </form>
            </td>
        </tr>
        <tr id="detail-<?= (int)$a->id ?>" style="display:none;">
            <td colspan="6">
                <div class="detail-grid">
                    <?php if ($a->website): ?><dl><dt>Website</dt><dd><a href="<?php echo View::e($a->website); ?>" target="_blank" style="color:#2563eb;"><?php echo View::e($a->website); ?></a></dd></dl><?php endif; ?>
                    <?php if ($a->tax_id): ?><dl><dt>Tax ID</dt><dd><?php echo View::e($a->tax_id); ?></dd></dl><?php endif; ?>
                    <?php if ($a->address || $a->city): ?><dl><dt>Address</dt><dd><?php echo View::e(trim($a->address . ' ' . $a->city . ' ' . $a->state . ' ' . $a->zip)); ?></dd></dl><?php endif; ?>
                    <?php if ($a->services_offered): ?><dl><dt>Services Offered</dt><dd><?php echo nl2br(View::e($a->services_offered)); ?></dd></dl><?php endif; ?>
                    <?php if ($a->notes): ?><dl><dt>Applicant Notes</dt><dd><?php echo nl2br(View::e($a->notes)); ?></dd></dl><?php endif; ?>
                    <?php if ($a->admin_notes): ?><dl><dt>Admin Notes</dt><dd><?php echo nl2br(View::e($a->admin_notes)); ?></dd></dl><?php endif; ?>
                    <dl><dt>IP Address</dt><dd><?php echo View::e($a->ip_address); ?></dd></dl>
                </div>
                <?php if ($a->status === 'pending'): ?>
                <form method="POST" action="<?= BH_APP_URL ?>/admin/supplier/status" style="margin-top:14px;display:flex;gap:10px;align-items:flex-start;flex-wrap:wrap;">
                    <?php echo Csrf::field(); ?>
                    <input type="hidden" name="id" value="<?= (int)$a->id ?>">
                    <textarea name="admin_notes" style="flex:1;min-width:220px;min-height:60px;padding:8px 12px;border-radius:7px;border:1.5px solid #d1d5db;font-size:13px;font-family:inherit;" placeholder="Add a note (optional)…"><?php echo View::e($a->admin_notes ?? ''); ?></textarea>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <button type="submit" name="status" value="approved" class="action-btn" style="background:#16a34a;padding:8px 20px;">
                            <i class="fa-solid fa-check" style="margin-right:6px;"></i>Approve
                        </button>
                        <button type="submit" name="status" value="rejected" class="action-btn" style="background:#dc2626;padding:8px 20px;">
                            <i class="fa-solid fa-xmark" style="margin-right:6px;"></i>Reject
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    </div>
</div>

<script>
function toggleDetail(id) {
    var row = document.getElementById('detail-' + id);
    var icon = document.getElementById('icon-' + id);
    if (row.style.display === 'none') {
        row.style.display = 'table-row';
        icon.className = 'fa-solid fa-chevron-up';
    } else {
        row.style.display = 'none';
        icon.className = 'fa-solid fa-chevron-down';
    }
}
</script>
<?php include BH_VIEWS . '/layout/footer.php'; ?>
</body>
</html>
