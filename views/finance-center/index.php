<?php
// Pull live KPIs for the featured CFO Report card
$cfo_mrr = 0; $cfo_cash = 0; $cfo_revenue = 0;
try {
    if (class_exists('BooksController')) {
        $m = BooksController::liveMetrics();
        $cfo_mrr     = (float)($m['mrr']           ?? 0);
        $cfo_cash    = (float)($m['cash_on_hand']  ?? 0);
        $cfo_revenue = (float)($m['total_revenue'] ?? 0);
    }
} catch (\Throwable $e) {}

$cards = [
    [
        'title'    => 'Budget',
        'desc'     => 'Track income and expenses against monthly budget targets by category.',
        'url'      => BH_APP_URL . '/budget',
        'icon'     => 'fa-solid fa-wallet',
        'color'    => '#10b981',
        'bg'       => '#f0fdf4',
        'stat'     => $stats['budget_categories'] ? $stats['budget_categories'] . ' categories' : null,
    ],
    [
        'title'    => 'Chart of Accounts',
        'desc'     => 'Manage your general ledger account structure and GL categories.',
        'url'      => BH_APP_URL . '/chart-of-accounts',
        'icon'     => 'fa-solid fa-book',
        'color'    => '#6366f1',
        'bg'       => '#eef2ff',
        'stat'     => $stats['coa_accounts'] ? $stats['coa_accounts'] . ' accounts' : null,
    ],
    [
        'title'    => 'Income Tracker',
        'desc'     => 'Monitor revenue streams and track recurring income over time.',
        'url'      => BH_APP_URL . '/income-tracker',
        'icon'     => 'fa-solid fa-chart-line',
        'color'    => '#3b82f6',
        'bg'       => '#eff6ff',
        'stat'     => $stats['income_this_month'] ? '$' . number_format($stats['income_this_month'], 2) . ' this month' : null,
    ],
    [
        'title'    => 'Mileage',
        'desc'     => 'Log business trips and calculate IRS mileage reimbursements.',
        'url'      => BH_APP_URL . '/mileage',
        'icon'     => 'fa-solid fa-car',
        'color'    => '#f59e0b',
        'bg'       => '#fffbeb',
        'stat'     => $stats['mileage_this_year'] ? number_format($stats['mileage_this_year'], 1) . ' mi this year' : null,
    ],
    [
        'title'    => 'Payroll',
        'desc'     => 'Process staff pay runs, manage deductions, and handle remittances.',
        'url'      => BH_APP_URL . '/payroll',
        'icon'     => 'fa-solid fa-money-check-dollar',
        'color'    => '#22c55e',
        'bg'       => '#f0fdf4',
        'stat'     => $stats['payroll_employees'] ? $stats['payroll_employees'] . ' active employees' : null,
    ],
    [
        'title'    => 'Reports',
        'desc'     => 'P&amp;L, expense summaries, invoice aging, and financial snapshots.',
        'url'      => BH_APP_URL . '/reports',
        'icon'     => 'fa-solid fa-chart-bar',
        'color'    => '#ef4444',
        'bg'       => '#fef2f2',
        'stat'     => null,
    ],
    [
        'title'    => 'Vendors',
        'desc'     => 'Manage supplier records, contacts, and payment terms.',
        'url'      => BH_APP_URL . '/vendors',
        'icon'     => 'fa-solid fa-truck',
        'color'    => '#ec4899',
        'bg'       => '#fdf2f8',
        'stat'     => $stats['vendors_count'] ? $stats['vendors_count'] . ' vendors' : null,
    ],
    [
        'title'    => 'Tax Center',
        'desc'     => in_array('tax-ca', array_column(DB::all("SELECT module_id FROM bh_modules WHERE active=1") ?: [], 'module_id'))
                        ? 'GST/HST/PST, CRA remittances, and year-end tax preparation.'
                        : 'Sales tax, 1099 prep, and US tax reporting.',
        'url'      => in_array('tax-ca', array_column(DB::all("SELECT module_id FROM bh_modules WHERE active=1") ?: [], 'module_id'))
                        ? BH_APP_URL . '/tax-ca'
                        : BH_APP_URL . '/tax',
        'icon'     => 'fa-solid fa-percent',
        'color'    => '#8b5cf6',
        'bg'       => '#f5f3ff',
        'stat'     => null,
    ],
];

usort($cards, fn($a, $b) => strcasecmp($a['title'], $b['title']));
?>

<!-- Page header -->
<div style="margin-bottom:28px;">
    <h1 style="font-size:22px;font-weight:700;color:#111827;margin:0 0 4px;">Finance Center</h1>
    <p style="font-size:14px;color:#6b7280;margin:0;">All financial tools in one place.</p>
</div>

<!-- Featured CFO Report card -->
<a href="<?= BH_APP_URL ?>/books" style="text-decoration:none;display:block;margin-bottom:24px;background:linear-gradient(135deg,#1e1b4b 0%,#3730a3 45%,#6366f1 100%);border-radius:16px;padding:28px 32px;color:#fff;position:relative;overflow:hidden;box-shadow:0 8px 24px rgba(55,48,163,.25);">

    <!-- Gold ribbon -->
    <div style="position:absolute;top:18px;right:24px;background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#78350f;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:5px 12px;border-radius:20px;box-shadow:0 2px 6px rgba(0,0,0,.15);">
        <i class="fa-solid fa-crown" style="margin-right:4px;"></i> Premium
    </div>

    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div style="width:72px;height:72px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid fa-chart-pie" style="font-size:34px;color:#fbbf24;"></i>
        </div>
        <div style="flex:1;min-width:240px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;font-weight:700;opacity:.85;margin-bottom:4px;">Executive Dashboard</div>
            <div style="font-size:22px;font-weight:700;margin-bottom:6px;">CFO Report</div>
            <div style="font-size:13px;opacity:.85;line-height:1.5;">Live KPIs, P&amp;L summary, MRR trends, EBITDA, AR &amp; cash position &mdash; all auto-populated from your existing data.</div>
        </div>

        <!-- Live stat pills -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 14px;min-width:110px;text-align:center;">
                <div style="font-size:10px;opacity:.8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin-bottom:4px;">MRR</div>
                <div style="font-size:18px;font-weight:700;">$<?= number_format($cfo_mrr, 0) ?></div>
            </div>
            <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 14px;min-width:110px;text-align:center;">
                <div style="font-size:10px;opacity:.8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin-bottom:4px;">Cash</div>
                <div style="font-size:18px;font-weight:700;">$<?= number_format($cfo_cash, 0) ?></div>
            </div>
            <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 14px;min-width:110px;text-align:center;">
                <div style="font-size:10px;opacity:.8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin-bottom:4px;">Revenue MTD</div>
                <div style="font-size:18px;font-weight:700;">$<?= number_format($cfo_revenue, 0) ?></div>
            </div>
        </div>
    </div>
</a>

<!-- Cards grid -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;">
<?php foreach ($cards as $card): ?>
    <a href="<?= $card['url'] ?>" style="text-decoration:none;display:flex;flex-direction:column;background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:22px;transition:box-shadow .15s,border-color .15s;" onmouseover="this.style.borderColor='#d1d5db';this.style.boxShadow='0 4px 16px rgba(0,0,0,.07)'" onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
        <div style="width:64px;height:64px;border-radius:12px;background:<?= $card['bg'] ?>;display:flex;align-items:center;justify-content:center;margin-bottom:14px;flex-shrink:0;">
            <i class="<?= $card['icon'] ?>" style="font-size:28px;color:<?= $card['color'] ?>;"></i>
        </div>
        <div style="font-size:15px;font-weight:700;color:#111827;margin-bottom:6px;"><?= $card['title'] ?></div>
        <div style="font-size:13px;color:#6b7280;line-height:1.6;flex:1;"><?= $card['desc'] ?></div>
        <?php if ($card['stat']): ?>
        <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f3f4f6;font-size:12px;font-weight:600;color:<?= $card['color'] ?>;">
            <?= $card['stat'] ?>
        </div>
        <?php endif; ?>
    </a>
<?php endforeach; ?>
</div>
