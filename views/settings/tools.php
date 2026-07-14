<?php $page_title = 'Tools'; ?>
<?php
$tools = [
    ['url'=>'/ai-helper/settings',      'icon'=>'fa-robot',               'color'=>'#8b5cf6', 'title'=>'AI Helper',                  'desc'=>'Configure AI providers and chat settings.',        'mod'=>'ai-helper'],
    ['url'=>'/marketplace',              'icon'=>'fa-store',               'color'=>'#f59e0b', 'title'=>'Marketplace',                'desc'=>'Browse and install modules.',                      'mod'=>null],
    ['url'=>'/settings/portal',          'icon'=>'fa-globe',               'color'=>'#10b981', 'title'=>'Customer Portal',            'desc'=>'Manage the self-service client portal.',           'mod'=>'portal'],
    ['url'=>'/settings/stripe',          'icon'=>'fa-credit-card',         'color'=>'#635bff', 'title'=>'Online Payments',            'desc'=>'Connect Stripe to accept invoice payments.',       'mod'=>'stripe_connect'],
    ['url'=>'/invoices/settings',        'icon'=>'fa-file-invoice-dollar', 'color'=>'#16a34a', 'title'=>'Invoice and Payment Methods','desc'=>'Invoice settings, payment gateways and options.', 'mod'=>'invoices'],
    ['url'=>'/customers/import',         'icon'=>'fa-file-import',         'color'=>'#0ea5e9', 'title'=>'Import Customers',           'desc'=>'Bulk import customers from CSV.',                  'mod'=>null],
    ['url'=>'/invoices/import',          'icon'=>'fa-file-arrow-up',       'color'=>'#f97316', 'title'=>'Import Invoices',            'desc'=>'Bulk import invoices from CSV.',                   'mod'=>null],
    ['url'=>'/tickets/import',           'icon'=>'fa-ticket',              'color'=>'#ec4899', 'title'=>'Import Tickets',             'desc'=>'Bulk import tickets from CSV.',                    'mod'=>null],
    ['url'=>'/settings/ticket-settings', 'icon'=>'fa-headset',             'color'=>'#ef4444', 'title'=>'Ticket Settings',            'desc'=>'IMAP, departments, SLA and ticket options.',       'mod'=>'tickets'],
    ['url'=>'/settings/screensaver',     'icon'=>'fa-desktop',             'color'=>'#64748b', 'title'=>'Screensaver',                'desc'=>'Configure the idle screensaver display.',          'mod'=>null],
    ['url'=>'/settings/users',           'icon'=>'fa-users',               'color'=>'#6366f1', 'title'=>'Users',                     'desc'=>'Manage staff accounts and permissions.',           'mod'=>null],
    ['url'=>'/settings/modules',         'icon'=>'fa-puzzle-piece',        'color'=>'#14b8a6', 'title'=>'Modules',                   'desc'=>'Install, enable or remove modules.',               'mod'=>null],
    ['url'=>'/audit',                    'icon'=>'fa-clipboard-list',      'color'=>'#0891b2', 'title'=>'Audit Log',                 'desc'=>'View tenant activity log, filter and export.',     'mod'=>'audit'],
];
try {
    $active_mods = array_column(DB::all("SELECT module_id FROM bh_modules WHERE active = 1"), 'module_id');
} catch (\Throwable $e) { $active_mods = []; }
?>
<div class="page-title-area">
    <h1 style="margin:0;font-size:20px;font-weight:700;">
        <i class="fa-solid fa-screwdriver-wrench" style="color:var(--pfx-primary);margin-right:8px;"></i>Tools
    </h1>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:16px;">
    <?php foreach ($tools as $t):
        if ($t['mod'] && !in_array($t['mod'], $active_mods)) continue;
    ?>
    <a href="<?= BH_APP_URL . $t['url'] ?>"
       style="display:flex;flex-direction:column;align-items:center;text-align:center;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px 16px;text-decoration:none;color:inherit;"
       onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'"
       onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">
        <div style="width:54px;height:54px;border-radius:14px;background:<?= $t['color'] ?>20;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
            <i class="fa-solid <?= $t['icon'] ?>" style="font-size:22px;color:<?= $t['color'] ?>;"></i>
        </div>
        <div style="font-weight:700;font-size:.93em;margin-bottom:5px;color:#111827;"><?= htmlspecialchars($t['title']) ?></div>
        <div style="font-size:.79em;color:#6b7280;line-height:1.45;"><?= htmlspecialchars($t['desc']) ?></div>
    </a>
    <?php endforeach; ?>
</div>
