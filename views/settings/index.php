<?php $page_title = 'Settings'; ?>
<div class="page-title-area">
    <h1 style="margin:0;font-size:20px;font-weight:700;">
        <i class="fa-solid fa-gear" style="color:var(--pfx-primary);margin-right:8px;"></i>Settings
    </h1>
</div>

<div style="display:grid;grid-template-columns:200px 1fr;gap:20px;align-items:start;">

<!-- Settings sidebar nav -->
<div class="card" style="padding:0;overflow:hidden;">
    <?php
    $cur = Request::path();

    $snav = [
        '/settings'                  => ['fa-building',            'Company'],
        '/settings/users'            => ['fa-users',               'Users'],
        '/settings/mfa'              => ['fa-lock',                'Two-Factor Auth'],
        '/settings/ticket-settings'  => ['fa-headset',             'Ticket Settings'],
        '/settings/screensaver'      => ['fa-desktop',             'Screensaver'],
    ];

    // Tool links shown directly in sidebar
    $tool_links = [
        '/ai-helper/settings'     => ['fa-robot',               'AI Helper Settings'],
	'/invoices/settings'      => ['fa-file-invoice-dollar', 'Advanced Payments'],
        '/marketplace'            => ['fa-store',               'Marketplace'],
        '/importer'       => ['fa-file-import',         'Import Customers'],
        '/invoice-importer'        => ['fa-file-arrow-up',       'Import Invoices'],
        '/ticket-importer'         => ['fa-ticket',              'Import Tickets'],
    ];

    $module_settings_nav = [];
    try {
        $active     = DB::all("SELECT module_id FROM bh_modules WHERE active = 1");
        $active_ids = array_column($active, 'module_id');
        $module_nav_map = [
            'portal'         => ['fa-globe',        'Client Portal',   '/settings/portal'],
            'stripe_connect' => ['fa-credit-card',  'Online Payments', '/settings/stripe'],
            'audit'          => ['fa-clipboard-list','Audit Log',       '/audit'],
        ];
        foreach ($module_nav_map as $mod_id => [$icon, $label, $url]) {
            if (in_array($mod_id, $active_ids)) {
                $module_settings_nav[$url] = [$icon, $label];
            }
        }
        // Auto-discovered: any active module that called ModuleLoader::settingsNav() in boot().
        if (class_exists('ModuleLoader')) {
            foreach (ModuleLoader::$settingsNav as $entry) {
                $module_settings_nav[$entry['url']] = [$entry['icon'], $entry['label']];
            }
        }
    } catch (\Throwable $e) {}

    $all_nav = array_merge($snav, $module_settings_nav);
    ?>
    <?php foreach ($all_nav as $url => [$icon, $label]):
        $active = ($cur === $url) || ($url !== '/settings' && strpos($cur, $url) === 0);
    ?>
    <a href="<?php echo BH_APP_URL . $url; ?>"
       style="display:flex;align-items:center;gap:10px;padding:12px 16px;font-size:13px;font-weight:500;text-decoration:none;border-bottom:1px solid #f3f4f6;color:<?php echo $active?'#3b82f6':'#374151'; ?>;background:<?php echo $active?'#eff6ff':'transparent'; ?>;">
        <i class="fa-solid <?php echo $icon; ?>" style="color:<?php echo $active?'#3b82f6':'#9ca3af'; ?>;width:16px;text-align:center;"></i>
        <?php echo $label; ?>
    </a>
    <?php endforeach; ?>

    <div style="padding:8px 16px 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;border-top:1px solid #f3f4f6;margin-top:4px;">Tools</div>
    <?php foreach ($tool_links as $url => [$icon, $label]):
        $active = ($cur === $url) || (strpos($cur, $url) === 0);
    ?>
    <a href="<?php echo BH_APP_URL . $url; ?>"
       style="display:flex;align-items:center;gap:10px;padding:12px 16px;font-size:13px;font-weight:500;text-decoration:none;border-bottom:1px solid #f3f4f6;color:<?php echo $active?'#3b82f6':'#374151'; ?>;background:<?php echo $active?'#eff6ff':'transparent'; ?>;">
        <i class="fa-solid <?php echo $icon; ?>" style="color:<?php echo $active?'#3b82f6':'#9ca3af'; ?>;width:16px;text-align:center;"></i>
        <?php echo $label; ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- Company settings form -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Company Settings</h3>
    </div>
    <div class="card-body">
    <form method="POST" action="<?php echo BH_APP_URL; ?>/settings" enctype="multipart/form-data">
        <?php echo Csrf::field(); ?>

        <!-- Company Info -->
        <h4 style="font-size:13px;font-weight:700;color:#374151;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;">
            <i class="fa-solid fa-building" style="color:var(--pfx-primary);margin-right:7px;"></i>Company Info
        </h4>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" value="<?php echo View::e($settings['company_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Company Email</label>
                <input type="email" name="company_email" class="form-control" value="<?php echo View::e($settings['company_email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="tel" name="company_phone" class="form-control" value="<?php echo View::e($settings['company_phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <input type="text" name="company_address" class="form-control" value="<?php echo View::e($settings['company_address'] ?? ''); ?>">
            </div>
        </div>

        <!-- Logo -->
        <h4 style="font-size:13px;font-weight:700;color:#374151;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;">
            <i class="fa-solid fa-image" style="color:var(--pfx-primary);margin-right:7px;"></i>Logo
        </h4>
        <div style="margin-bottom:20px;">
            <?php if (!empty($settings['company_logo'])): ?>
            <div style="margin-bottom:10px;display:flex;align-items:center;gap:14px;">
                <img src="<?php echo View::e($settings['company_logo']); ?>" alt="Logo"
                     style="max-height:48px;max-width:200px;border-radius:6px;border:1px solid #e5e7eb;padding:4px;background:#fff;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#dc2626;cursor:pointer;">
                    <input type="checkbox" name="remove_logo" value="1"> Remove logo
                </label>
            </div>
            <?php endif; ?>
            <input type="file" name="company_logo" class="form-control" accept=".png,.jpg,.jpeg,.gif,.svg,.webp"
                   style="padding:6px;">
            <small style="color:#9ca3af;">PNG, JPG, SVG or WebP. Displayed in the sidebar.</small>
        </div>

        <!-- Locale & Time -->
        <h4 style="font-size:13px;font-weight:700;color:#374151;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;">
            <i class="fa-solid fa-clock" style="color:var(--pfx-primary);margin-right:7px;"></i>Locale & Time
        </h4>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
            <div class="form-group">
                <label class="form-label">Timezone</label>
                <select name="timezone" class="form-control">
                    <?php
                    $cur_tz = $settings['timezone'] ?? (defined('BH_TIMEZONE') ? BH_TIMEZONE : 'America/Chicago');
                    $tz_groups = [
                        'US & Canada' => [
                            'America/New_York'    => 'Eastern (ET)',
                            'America/Chicago'     => 'Central (CT)',
                            'America/Denver'      => 'Mountain (MT)',
                            'America/Phoenix'     => 'Arizona (no DST)',
                            'America/Los_Angeles' => 'Pacific (PT)',
                            'America/Anchorage'   => 'Alaska (AKT)',
                            'Pacific/Honolulu'    => 'Hawaii (HST)',
                        ],
                        'Europe' => [
                            'Europe/London'    => 'London (GMT/BST)',
                            'Europe/Paris'     => 'Paris (CET)',
                            'Europe/Berlin'    => 'Berlin (CET)',
                            'Europe/Madrid'    => 'Madrid (CET)',
                            'Europe/Rome'      => 'Rome (CET)',
                            'Europe/Amsterdam' => 'Amsterdam (CET)',
                            'Europe/Brussels'  => 'Brussels (CET)',
                            'Europe/Zurich'    => 'Zurich (CET)',
                            'Europe/Stockholm' => 'Stockholm (CET)',
                            'Europe/Oslo'      => 'Oslo (CET)',
                            'Europe/Copenhagen'=> 'Copenhagen (CET)',
                            'Europe/Helsinki'  => 'Helsinki (EET)',
                            'Europe/Athens'    => 'Athens (EET)',
                            'Europe/Bucharest' => 'Bucharest (EET)',
                            'Europe/Kiev'      => 'Kyiv (EET)',
                            'Europe/Moscow'    => 'Moscow (MSK)',
                        ],
                        'Asia / Pacific' => [
                            'Asia/Dubai'       => 'Dubai (GST)',
                            'Asia/Kolkata'     => 'India (IST)',
                            'Asia/Dhaka'       => 'Bangladesh (BST)',
                            'Asia/Bangkok'     => 'Bangkok (ICT)',
                            'Asia/Singapore'   => 'Singapore (SGT)',
                            'Asia/Hong_Kong'   => 'Hong Kong (HKT)',
                            'Asia/Shanghai'    => 'China (CST)',
                            'Asia/Tokyo'       => 'Tokyo (JST)',
                            'Asia/Seoul'       => 'Seoul (KST)',
                            'Australia/Sydney' => 'Sydney (AEST)',
                            'Australia/Melbourne' => 'Melbourne (AEST)',
                            'Australia/Perth'  => 'Perth (AWST)',
                            'Pacific/Auckland' => 'Auckland (NZST)',
                        ],
                        'Americas' => [
                            'America/Toronto'   => 'Toronto (ET)',
                            'America/Vancouver' => 'Vancouver (PT)',
                            'America/Mexico_City' => 'Mexico City (CST)',
                            'America/Sao_Paulo' => 'S o Paulo (BRT)',
                            'America/Argentina/Buenos_Aires' => 'Buenos Aires (ART)',
                            'America/Bogota'    => 'Bogot  (COT)',
                            'America/Lima'      => 'Lima (PET)',
                            'America/Santiago'  => 'Santiago (CLT)',
                        ],
                        'Africa / Middle East' => [
                            'Africa/Cairo'        => 'Cairo (EET)',
                            'Africa/Johannesburg' => 'Johannesburg (SAST)',
                            'Africa/Lagos'        => 'Lagos (WAT)',
                            'Africa/Nairobi'      => 'Nairobi (EAT)',
                            'Asia/Jerusalem'      => 'Jerusalem (IST)',
                            'Asia/Riyadh'         => 'Riyadh (AST)',
                            'Asia/Tehran'         => 'Tehran (IRST)',
                        ],
                        'UTC' => ['UTC' => 'UTC'],
                    ];
                    foreach ($tz_groups as $group => $zones): ?>
                    <optgroup label="<?php echo View::e($group); ?>">
                        <?php foreach ($zones as $tz_val => $tz_label): ?>
                        <option value="<?php echo $tz_val; ?>" <?php echo $cur_tz === $tz_val ? 'selected' : ''; ?>>
                            <?php echo View::e($tz_label); ?>
                        </option>
                        <?php endforeach; ?>
                    </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Default Currency</label>
                <select name="default_currency" class="form-control">
                    <?= Currencies::renderOptions($settings['default_currency'] ?? 'USD', true) ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Default Tax Rate (%)</label>
                <input type="number" name="default_tax_rate" class="form-control" value="<?php echo View::e($settings['default_tax_rate'] ?? '0'); ?>" min="0" max="100" step="0.01">
            </div>
        </div>

        <!-- App Behavior -->
        <h4 style="font-size:13px;font-weight:700;color:#374151;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;">
            <i class="fa-solid fa-sliders" style="color:var(--pfx-primary);margin-right:7px;"></i>App Behavior
        </h4>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
            <div class="form-group">
                <label class="form-label">After Login, Go To</label>
                <select name="login_redirect" class="form-control">
                    <?php
                    $cur_redir  = $settings['login_redirect'] ?? '/dashboard';
                    $redir_opts = [
                        '/dashboard' => 'Dashboard',
                        '/tickets'   => 'Tickets',
                        '/clients'   => 'Clients',
                        '/invoices'  => 'Invoices',
                        '/employees' => 'Employees',
                    ];
                    foreach ($redir_opts as $rv => $rl): ?>
                    <option value="<?php echo $rv; ?>" <?php echo $cur_redir === $rv ? 'selected' : ''; ?>><?php echo $rl; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Invoice Prefix</label>
                <input type="text" name="invoice_prefix" class="form-control" value="<?php echo View::e($settings['invoice_prefix'] ?? 'INV'); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Mileage Rate ($/mile)</label>
                <input type="number" name="mileage_rate" class="form-control" value="<?php echo View::e($settings['mileage_rate'] ?? '0.67'); ?>" step="0.001">
            </div>
        </div>

        <!-- Appearance -->
        <h4 style="font-size:13px;font-weight:700;color:#374151;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;">
            <i class="fa-solid fa-palette" style="color:var(--pfx-primary);margin-right:7px;"></i>Appearance
        </h4>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <div class="form-group">
                <label class="form-label">Sidebar Color</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" name="sidebar_color" id="sidebar_color" class="form-control"
                           value="<?php echo View::e($settings['sidebar_color'] ?? '#1e293b'); ?>"
                           style="height:40px;padding:4px 6px;width:60px;flex-shrink:0;"
                           oninput="document.getElementById('sidebar_color_txt').value=this.value">
                    <input type="text" id="sidebar_color_txt" class="form-control" style="font-family:monospace;"
                           value="<?php echo View::e($settings['sidebar_color'] ?? '#1e293b'); ?>"
                           oninput="document.getElementById('sidebar_color').value=this.value">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Accent Color</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" name="accent_color" id="accent_color" class="form-control"
                           value="<?php echo View::e($settings['accent_color'] ?? '#3b82f6'); ?>"
                           style="height:40px;padding:4px 6px;width:60px;flex-shrink:0;"
                           oninput="document.getElementById('accent_color_txt').value=this.value">
                    <input type="text" id="accent_color_txt" class="form-control" style="font-family:monospace;"
                           value="<?php echo View::e($settings['accent_color'] ?? '#3b82f6'); ?>"
                           oninput="document.getElementById('accent_color').value=this.value">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Sidebar Text Color</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" name="sidebar_text_color" id="sidebar_text_color" class="form-control"
                           value="<?php echo View::e($settings['sidebar_text_color'] ?? '#cbd5e1'); ?>"
                           style="width:46px;height:36px;padding:2px;cursor:pointer;"
                           oninput="document.getElementById('sidebar_text_color_txt').value=this.value">
                    <input type="text" id="sidebar_text_color_txt" class="form-control" style="font-family:monospace;"
                           value="<?php echo View::e($settings['sidebar_text_color'] ?? '#cbd5e1'); ?>"
                           oninput="document.getElementById('sidebar_text_color').value=this.value">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Submenu Background Color</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" name="submenu_color" id="submenu_color" class="form-control"
                           value="<?php echo View::e($settings['submenu_color'] ?? '#000000'); ?>"
                           style="height:40px;padding:4px 6px;width:60px;flex-shrink:0;"
                           oninput="document.getElementById('submenu_color_txt').value=this.value">
                    <input type="text" id="submenu_color_txt" class="form-control" style="font-family:monospace;"
                           value="<?php echo View::e($settings['submenu_color'] ?? '#000000'); ?>"
                           oninput="document.getElementById('submenu_color').value=this.value">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Submenu Text Color</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" name="submenu_text_color" id="submenu_text_color" class="form-control"
                           value="<?php echo View::e($settings['submenu_text_color'] ?? '#94a3b8'); ?>"
                           style="height:40px;padding:4px 6px;width:60px;flex-shrink:0;"
                           oninput="document.getElementById('submenu_text_color_txt').value=this.value">
                    <input type="text" id="submenu_text_color_txt" class="form-control" style="font-family:monospace;"
                           value="<?php echo View::e($settings['submenu_text_color'] ?? '#94a3b8'); ?>"
                           oninput="document.getElementById('submenu_text_color').value=this.value">
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom:16px;">
            <div class="col-md-6">
                <label class="form-label">Nav Menu Font Size</label>
                <select name="nav_font_size" class="form-control">
                    <?php foreach(['11px','12px','13px','14px','15px','16px','17px','18px'] as $sz): ?>
                    <option value="<?= $sz ?>" <?= ($settings['nav_font_size'] ?? '13px') === $sz ? 'selected' : '' ?>><?= $sz ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nav Submenu Font Size</label>
                <select name="nav_sub_font_size" class="form-control">
                    <?php foreach(['11px','12px','13px','14px','15px','16px','17px','18px'] as $sz): ?>
                    <option value="<?= $sz ?>" <?= ($settings['nav_sub_font_size'] ?? '13px') === $sz ? 'selected' : '' ?>><?= $sz ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Login Page -->
        <h4 style="font-size:13px;font-weight:700;color:#374151;margin:8px 0 14px;padding-bottom:8px;border-bottom:1px solid #f3f4f6;">
            <i class="fa-solid fa-right-to-bracket" style="color:var(--pfx-primary);margin-right:7px;"></i>Login Page
        </h4>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <div class="form-group">
                <label class="form-label">Login Background Color</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="color" name="login_bg_color" id="login_bg_color" class="form-control"
                           value="<?php echo View::e($settings['login_bg_color'] ?? '#0f172a'); ?>"
                           style="height:40px;padding:4px 6px;width:60px;flex-shrink:0;"
                           oninput="document.getElementById('login_bg_color_txt').value=this.value">
                    <input type="text" id="login_bg_color_txt" class="form-control" style="font-family:monospace;"
                           value="<?php echo View::e($settings['login_bg_color'] ?? '#0f172a'); ?>"
                           oninput="document.getElementById('login_bg_color').value=this.value">
                </div>
                <small style="color:#9ca3af;display:block;margin-top:6px;">Fills the area around the login card. Used when no background image is set.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Login Background Image</label>
                <?php if (!empty($settings['login_bg_image'])): ?>
                <div style="margin-bottom:8px;">
                    <img src="<?php echo View::e($settings['login_bg_image']); ?>" alt="Login background"
                         style="max-height:48px;border-radius:6px;border:1px solid #e5e7eb;">
                    <label style="display:block;font-size:12px;color:#6b7280;margin-top:6px;">
                        <input type="checkbox" name="remove_login_bg" value="1"> Remove background image
                    </label>
                </div>
                <?php endif; ?>
                <input type="file" name="login_bg" class="form-control" accept=".png,.jpg,.jpeg,.gif,.webp">
                <small style="color:#9ca3af;display:block;margin-top:6px;">Overrides the color. The customer logo (above) sits on top of the card.</small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save" style="margin-right:6px;"></i>Save Settings
        </button>
    </form>
    </div>
</div>
</div>