<?php
$cards = [
    [
        'title' => 'AI Growth',
        'desc'  => 'AI-powered lead generation, cold email sequences, and outreach automation.',
        'url'   => BH_APP_URL . '/agg',
        'icon'  => 'fa-solid fa-rocket',
        'color' => '#6366f1',
        'bg'    => '#eef2ff',
        'stat'  => null,
    ],
    [
        'title' => 'Leads',
        'desc'  => 'Track prospects through your pipeline from first contact to close.',
        'url'   => BH_APP_URL . '/leads',
        'icon'  => 'fa-solid fa-chart-line',
        'color' => '#f59e0b',
        'bg'    => '#fffbeb',
        'stat'  => $stats['leads_open'] ? $stats['leads_open'] . ' open leads' : null,
    ],
    [
        'title' => 'Proposals',
        'desc'  => 'Create, send, and track professional proposals and estimates for clients.',
        'url'   => BH_APP_URL . '/proposals',
        'icon'  => 'fa-solid fa-file-signature',
        'color' => '#3b82f6',
        'bg'    => '#eff6ff',
        'stat'  => $stats['proposals_open'] ? $stats['proposals_open'] . ' open proposals' : null,
    ],
    [
        'title' => 'Contracts',
        'desc'  => 'Manage service agreements, MSAs, and recurring contract terms.',
        'url'   => BH_APP_URL . '/contracts',
        'icon'  => 'fa-solid fa-file-contract',
        'color' => '#7c3aed',
        'bg'    => '#f5f3ff',
        'stat'  => $stats['contracts_active'] ? $stats['contracts_active'] . ' active' : null,
    ],
    [
        'title' => 'Assessments',
        'desc'  => 'Send IT risk assessments and business growth assessments to prospects.',
        'url'   => BH_APP_URL . '/assessments',
        'icon'  => 'fa-solid fa-clipboard-check',
        'color' => '#0891b2',
        'bg'    => '#ecfeff',
        'stat'  => $stats['assessments'] ? $stats['assessments'] . ' total' : null,
    ],
    [
        'title' => 'Assessment Inbox',
        'desc'  => 'Review completed assessment submissions from prospects and clients.',
        'url'   => BH_APP_URL . '/assessments-inbox',
        'icon'  => 'fa-solid fa-inbox',
        'color' => '#0891b2',
        'bg'    => '#ecfeff',
        'stat'  => null,
    ],
    [
        'title' => 'Newsletter',
        'desc'  => 'Manage subscriber lists and send targeted email campaigns.',
        'url'   => BH_APP_URL . '/newsletter',
        'icon'  => 'fa-solid fa-envelope-open-text',
        'color' => '#ec4899',
        'bg'    => '#fdf2f8',
        'stat'  => $stats['newsletter_subs'] ? $stats['newsletter_subs'] . ' subscribers' : null,
    ],
    [
        'title' => 'Products',
        'desc'  => 'Manage your product and service catalog with inventory tracking.',
        'url'   => BH_APP_URL . '/inventory/products',
        'icon'  => 'fa-solid fa-box',
        'color' => '#22c55e',
        'bg'    => '#f0fdf4',
        'stat'  => $stats['products'] ? $stats['products'] . ' products' : null,
    ],
    [
        'title' => 'Invoices',
        'desc'  => 'Create, send, and track customer invoices and payments.',
        'url'   => BH_APP_URL . '/invoices',
        'icon'  => 'fa-solid fa-file-invoice-dollar',
        'color' => '#3b82f6',
        'bg'    => '#eff6ff',
        'stat'  => $stats['invoices_unpaid'] ? $stats['invoices_unpaid'] . ' unpaid' : null,
    ],
    [
        'title' => 'Purchase Orders',
        'desc'  => 'Order stock from vendors, receive shipments, track AP.',
        'url'   => BH_APP_URL . '/inventory/pos',
        'icon'  => 'fa-solid fa-file-circle-plus',
        'color' => '#f59e0b',
        'bg'    => '#fffbeb',
        'stat'  => $stats['pos_open'] ? $stats['pos_open'] . ' open' : null,
    ],
    [
        'title' => 'Inventory',
        'desc'  => 'Stock dashboard with KPIs, low-stock alerts, movements ledger, and transfers.',
        'url'   => BH_APP_URL . '/inventory',
        'icon'  => 'fa-solid fa-boxes-stacked',
        'color' => '#6366f1',
        'bg'    => '#eef2ff',
        'stat'  => null,
    ],
    [
        'title' => 'Warehouses',
        'desc'  => 'Multi-location stock tracking, transfers, and per-warehouse valuation.',
        'url'   => BH_APP_URL . '/inventory/warehouses',
        'icon'  => 'fa-solid fa-warehouse',
        'color' => '#0ea5e9',
        'bg'    => '#f0f9ff',
        'stat'  => $stats['warehouses'] ? $stats['warehouses'] . ' location' . ($stats['warehouses'] != 1 ? 's' : '') : null,
    ],
    [
        'title' => 'Vendors',
        'desc'  => 'Supplier directory used by POs, products, and expenses.',
        'url'   => BH_APP_URL . '/vendors',
        'icon'  => 'fa-solid fa-building',
        'color' => '#a855f7',
        'bg'    => '#faf5ff',
        'stat'  => $stats['vendors'] ? $stats['vendors'] . ' vendor' . ($stats['vendors'] != 1 ? 's' : '') : null,
    ],
];

usort($cards, fn($a, $b) => strcasecmp($a['title'], $b['title']));
?>
<div style="margin-bottom:28px;">
    <h1 style="font-size:22px;font-weight:700;color:#111827;margin:0 0 4px;">Sales Center</h1>
    <p style="font-size:14px;color:#6b7280;margin:0;">Pipeline, outreach, and sales tools in one place.</p>
</div>

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
