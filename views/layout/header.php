<?php
if (class_exists('Auth') && Auth::isClient()) {
    $req = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($req, '/portal') === false && strpos($req, '/invoices/pay') === false && strpos($req, '/invoices/view') === false) {
        header('Location: ' . BH_APP_URL . '/portal/dashboard'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo isset($page_title) ? View::e($page_title).' - ' : ''; echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo BH_ASSETS_URL; ?>/css/app.css?v=<?php echo BH_VERSION; ?>">
<link rel="stylesheet" href="<?php echo BH_ASSETS_URL; ?>/css/mobile.css?v=<?php echo BH_VERSION; ?>"><link rel="icon" href="<?php echo BH_APP_URL; ?>/favicon.ico?v=<?php echo BH_VERSION; ?>" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo BH_APP_URL; ?>/favicon-32x32.png?v=<?php echo BH_VERSION; ?>">
<link rel="apple-touch-icon" href="<?php echo BH_APP_URL; ?>/favicon-32x32.png?v=<?php echo BH_VERSION; ?>">

<?php if (isset($extra_css)) echo $extra_css; ?>
<style>
/* -- All original CSS vars (app.css depends on every one of these) -- */
:root {
    --pfx-sidebar-bg:       <?php echo View::e(bh_setting('sidebar_color','#1e293b')); ?>;
    --pfx-primary:          <?php echo View::e(bh_setting('accent_color','#3b82f6')); ?>;
    --pfx-body-bg:          #f4f4f5;
    --pfx-card-bg:          #ffffff;
    --pfx-sidebar-text:     <?php echo View::e(bh_setting('sidebar_text_color','#cbd5e1')); ?>;
    --pfx-sidebar-border:   rgba(255,255,255,0.1);
    --pfx-sidebar-active:   <?php echo View::e(bh_setting('accent_color','#3b82f6')); ?>;
    --pfx-sidebar-active-bg:rgba(59,130,246,0.15);
    --pfx-sidebar-icon:     rgba(255,255,255,0.5);
    --pfx-sidebar-muted:    #94a3b8;
    --pfx-sidebar-widget-bg:rgba(255,255,255,0.06);
    --pfx-nav-font-size:    <?php echo View::e(bh_setting('nav_font_size','14px')); ?>;
    --pfx-nav-sub-font-size:<?php echo View::e(bh_setting('nav_sub_font_size','12px')); ?>;
    --pfx-submenu-bg:       <?php echo View::e(bh_setting('submenu_color','rgba(0,0,0,0.18)')); ?>;
    --pfx-submenu-text:     <?php echo View::e(bh_setting('submenu_text_color','#94a3b8')); ?>;
}

/* -- New header features - inline so app.css doesn't need changing -- */

/* Breadcrumb */
.header-breadcrumb {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    color: #9ca3af;
    flex-shrink: 1;
    min-width: 0;
    overflow: hidden;
}
.header-breadcrumb a {
    color: #9ca3af;
    text-decoration: none;
    white-space: nowrap;
    transition: color .15s;
}
.header-breadcrumb a:hover { color: #374151; text-decoration: none; }
.header-breadcrumb .bc-sep  { color: #d1d5db; font-size: 10px; }
.header-breadcrumb .bc-current {
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Global search */
.header-search {
    flex: 1;
    max-width: 360px;
    margin: 0 8px;
    position: relative;
}
.header-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 12px;
    pointer-events: none;
}
.header-search input {
    width: 100%;
    height: 34px;
    padding: 0 60px 0 34px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    color: #374151;
    outline: none;
    transition: all .15s;
}
.header-search input::placeholder { color: #9ca3af; }
.header-search input:focus {
    background: #fff;
    border-color: var(--pfx-primary);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.header-search-kbd {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 10px;
    color: #9ca3af;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    padding: 1px 5px;
    font-family: monospace;
    pointer-events: none;
}

/* Header action buttons */
.header-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: var(--pfx-submenu-bg, transparent);
    border: 0;
    cursor: pointer;
    color: #6b7280;
    text-decoration: none;
    transition: all .15s;
    position: relative;
    font-size: 15px;
}
.header-btn:hover { background: #f3f4f6; color: #374151; text-decoration: none; }
.header-btn:active { transform: scale(0.94); }
/* Marketplace + Help buttons: fixed blue fill, white glyph (not tied to the
   accent color, so a light accent can never make them hard to see) */
a.header-btn[href$="/marketplace"],
a.header-btn[href$="/help"] {
    background: #3b82f6 !important;
    color: #fff !important;
}
a.header-btn[href$="/marketplace"]:hover,
a.header-btn[href$="/help"]:hover {
    background: #2563eb !important;
    color: #fff !important;
}
#notifBtn { background: #5f84ef !important; color: #fff !important; }
/* Embed mode - hide sidebar and header */
body.embed-mode #perfex-sidebar,
body.embed-mode #perfex-header,
body.embed-mode .mobile-sidebar-overlay { display: none !important; }
body.embed-mode #perfex-main { margin-left: 0 !important; }
body.embed-mode #perfex-content { padding-top: 0 !important; }
#notifBtn:hover { background: #4a6fd8 !important; color: #fff !important; }
.header-btn .notif-count {
    position: absolute;
    top: 2px; right: 2px;
    min-width: 16px; height: 16px;
    background: #ef4444;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    border-radius: 20px;
    border: 1.5px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    line-height: 1;
}
.header-divider {
    width: 1px;
    height: 22px;
    background: #e5e7eb;
    margin: 0 4px;
    flex-shrink: 0;
}

/* User chip */
.header-user-chip {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 4px 8px 4px 4px;
    border-radius: 9px;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
}
.header-user-chip:hover {
    background: #f3f4f6;
    border-color: #e5e7eb;
    text-decoration: none;
}
.header-user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.header-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
}
.header-user-caret { font-size: 9px; color: #9ca3af; }

/* Dropdown panels */
.header-dropdown { position: relative; }
.header-dropdown-panel {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    z-index: 1000;
    animation: hddropIn .15s ease;
    overflow: hidden;
}
.header-dropdown-panel.open { display: block; }
@keyframes hddropIn {
    from { opacity:0; transform:translateY(-6px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}

/* Notification panel */
.notif-panel { width: 340px; }
.notif-panel-header {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.notif-panel-header h4 { margin: 0; font-size: 13.5px; font-weight: 700; color: #111827; }
.notif-panel-header a  { font-size: 12px; color: var(--pfx-primary); }
.notif-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 12px 16px;
    border-bottom: 1px solid #f9fafb;
    transition: background .1s;
    text-decoration: none;
    color: inherit;
}
.notif-item:hover { background: #f9fafb; text-decoration: none; color: inherit; }
.notif-item:last-child { border-bottom: 0; }
.notif-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 13px;
}
.notif-icon.blue   { background:#dbeafe; color:#2563eb; }
.notif-icon.orange { background:#ffedd5; color:#ea580c; }
.notif-icon.red    { background:#fee2e2; color:#dc2626; }
.notif-icon.green  { background:#dcfce7; color:#16a34a; }
.notif-text { flex: 1; min-width: 0; }
.notif-text strong { font-size: 13px; font-weight: 600; color: #111827; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.notif-text p { margin: 2px 0 0; font-size: 11.5px; color: #6b7280; }
.notif-time { font-size: 11px; color: #9ca3af; display: block; margin-top: 3px; }
.notif-empty { padding: 28px 16px; text-align: center; color: #9ca3af; font-size: 13px; }
.notif-panel-footer {
    padding: 9px 16px;
    text-align: center;
    border-top: 1px solid #f3f4f6;
    background: #fafafa;
}
.notif-panel-footer a { font-size: 12px; color: var(--pfx-primary); font-weight: 500; }

/* User menu panel */
.user-menu-panel { width: 210px; }
.user-menu-header {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
}
.user-menu-header .u-name  { font-size: 13px; font-weight: 700; color: #111827; }
.user-menu-header .u-email { font-size: 11px; color: #6b7280; margin-top: 1px; }
.user-menu-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 14px;
    font-size: 13px;
    color: #374151;
    text-decoration: none;
    transition: background .1s;
}
.user-menu-item:hover { background: #f9fafb; text-decoration: none; color: #374151; }
.user-menu-item i { width: 14px; color: #9ca3af; font-size: 12px; }
.user-menu-item.u-danger { color: #dc2626; border-top: 1px solid #f3f4f6; }
.user-menu-item.u-danger i { color: #dc2626; }
.user-menu-item.u-danger:hover { background: #fef2f2; }

/* Mobile overlay */
.mobile-sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1050;
}
body.sidebar-mobile-open .mobile-sidebar-overlay { display: block; }

/* -- Section groups (collapsible) -- */
/* Section header row - clickable label, no icon */
.submenu-row {
    display: flex;
    align-items: center;
    padding: 10px 14px 3px;
    cursor: pointer;
    user-select: none;
}
.submenu-row:hover .submenu-folder-label { color: rgba(255,255,255,.65); }
.submenu-folder-label {
    flex: 1;
    font-size: var(--pfx-nav-font-size, 14px);
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--bh-nav-text-muted, rgba(255,255,255,.35));
    transition: color .15s;
}
/* Main nav (top-level) items honor the Nav Menu Font Size setting */
#side-menu > li > a {
    font-size: var(--pfx-nav-font-size, 14px) !important;
}
.submenu-arrow {
    font-size: 11px;
    color: rgba(255,255,255,.25);
    transition: transform .2s, color .15s;
    flex-shrink: 0;
}
.submenu-row:hover .submenu-arrow { color: rgba(255,255,255,.5); }
.has-submenu.open > .submenu-row .submenu-arrow { transform: rotate(180deg); }
/* Hidden legacy refs */
.submenu-main-link { display: none; }
.submenu-chevron-btn { display: none; }
/* Children - same style and size as top-level items */
.submenu {
    list-style: none; margin: 0; padding: 2px 0 6px;
    max-height: 0; overflow: hidden;
    transition: max-height .3s ease;
    background: var(--pfx-submenu-bg, transparent);
}
.has-submenu.open > .submenu { max-height: 600px; }
#perfex-sidebar .submenu li a {
    display: flex; align-items: center; gap: 10px;
    padding: 7px 14px;
    margin: 1px 6px;
    font-size: var(--pfx-nav-sub-font-size, 14px) !important;
    color: var(--pfx-submenu-text, var(--pfx-sidebar-text));
    text-decoration: none;
    border-radius: 6px;
    transition: color .15s, background .15s;
}
#perfex-sidebar .submenu li a i { font-size: 14px; opacity: .7; width: 16px; text-align: center; }
.submenu li a:hover { color: #fff; background: rgba(255,255,255,0.08); text-decoration: none; }
.submenu li a:hover i { opacity: 1; }
.submenu li.active > a { color: #fff; background: var(--pfx-sidebar-active-bg, rgba(59,130,246,.15)); font-weight: 600; }
.submenu li.active > a i { color: var(--pfx-sidebar-active); opacity: 1; }

/* -- Global font size reset ----------------------------------------------- */
body, html { font-size: 15px; }
.perfex-table td, .perfex-table th { font-size: 14px; }
.card-body, .card-header, .card-title { font-size: 14px; }
.form-control, .form-label, select.form-control { font-size: 14px; }
.btn { font-size: 14px; }
p, li, span, a, div { font-size: inherit; }
.page-subtitle, .badge { font-size: 12px; }
</style>
</head>
<body class="sidebar-dark<?php if(!empty($_GET['embed'])) echo ' embed-mode'; ?>">
<div id="perfex-wrapper">

<!-- Mobile overlay -->
<div class="mobile-sidebar-overlay mobile-overlay" id="mobileSidebarOverlay"></div>

<!-- ===================================================
     SIDEBAR  (structure identical to original)
==================================================== -->
<div id="perfex-sidebar">
    <div class="sidebar-logo-area" style="height:90px;padding:8px 14px;">
        <?php
        $logo_url = bh_setting('company_logo', '');
        $co_name  = View::e(bh_setting('company_name', BH_APP_NAME));
        if ($logo_url): ?>
        <a href="<?php echo BH_APP_URL; ?>/dashboard" style="display:block;text-decoration:none;">
            <img src="<?php echo View::e($logo_url); ?>" alt="<?php echo $co_name; ?>"
                 style="max-height:74px;max-width:200px;width:auto;object-fit:contain;display:block;">
        </a>
        <?php else: ?>
        <span class="site-title">
            <i class="fa-solid fa-shield-halved" style="margin-right:8px;color:var(--pfx-primary);"></i><?php echo $co_name; ?>
        </span>
        <?php endif; ?>
    </div>

    <?php if ($auth_user): ?>
    <div class="sidebar-user-profile" style="margin-top:12px;">
        <a href="<?php echo BH_APP_URL; ?>/settings/users/<?php echo $auth_user->id; ?>/edit"
           class="profile-toggle"
           style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;text-decoration:none;color:var(--pfx-sidebar-text);background:var(--pfx-sidebar-widget-bg);">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--pfx-primary);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                <?php echo strtoupper(substr($auth_user->name, 0, 1)); ?>
            </div>
            <div style="min-width:0;">
                <div style="font-size:13px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    <?php echo View::e($auth_user->name); ?>
                </div>
                <div style="font-size:11px;opacity:.6;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    <?php echo View::e(ucfirst($auth_user->role)); ?>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>

    <!-- Nav -->
    <ul id="side-menu" class="sidebar-nav" style="margin-top:12px;">
        <?php
        $current  = Request::path();
        $core_nav = [
            'dashboard' => ['label'=>'Dashboard','url'=>'/dashboard','icon'=>'fa-solid fa-gauge-high','priority'=>5],
        ];
        // Read nav from bhpsa_master - shared across all tenants
        try {
            $_master_pdo = new PDO('mysql:host='.BH_DB_HOST.';dbname='.BH_DB_NAME.';charset=utf8mb4', BH_DB_USER, BH_DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ]);
            $_has_db_nav = (int)$_master_pdo->query('SELECT COUNT(*) FROM bh_nav_items')->fetchColumn() > 0;
        } catch (Throwable $_e) { $_has_db_nav = false; $_master_pdo = null; }
        // Always include active-module nav ($nav = booted/active modules). The
        // bh_nav_items 'hidden' flag below still removes items; custom hub rows
        // are injected from bh_nav_items. So: shows iff active AND not hidden.
        $all_nav = array_merge($core_nav, $nav ?? []);

        // -- Permission filtering ----------------------------------------------
        // Admins always see everything.
        // Staff see only modules explicitly granted in bh_user_permissions.
        //   No rows saved = no modules (just Dashboard).
        // Clients see nothing (portal only).
        $always_visible = ['dashboard'];
        if ($auth_user && $auth_user->role === 'admin') {
            // full access - no filtering
        } elseif ($auth_user && in_array($auth_user->role, ['staff', 'contractor'])) {
            try {
                $perm_rows = DB::all('SELECT module_id FROM bh_user_permissions WHERE user_id = ? AND granted = 1', [$auth_user->id]);
                $permitted = array_column($perm_rows, 'module_id');
                foreach ($all_nav as $k => $item) {
                    if (in_array($k, $always_visible)) continue;
                    if (!in_array($k, $permitted)) unset($all_nav[$k]);
                }
            } catch (Throwable $_e) {
                // On DB error show nothing extra
                foreach ($all_nav as $k => $item) {
                    if (!in_array($k, $always_visible)) unset($all_nav[$k]);
                }
            }
        } else {
            // client or unknown - dashboard only
            $all_nav = array_intersect_key($all_nav, array_flip($always_visible));
        }

        // -- NavMenu DB overrides (ordering, hide, rename, folders) ------------
        try {
            $_nm = isset($_master_pdo) ? array_map(fn($r)=>(object)(array)$r, $_master_pdo->query('SELECT * FROM bh_nav_items ORDER BY sort_order ASC')->fetchAll()) : DB::all('SELECT * FROM bh_nav_items ORDER BY sort_order ASC');
            if (!empty($_nm)) {
                $_cfg = [];
                foreach ($_nm as $_r) $_cfg[$_r->nav_key] = $_r;

                foreach ($all_nav as $_k => &$_it) {
                    $_it['children']  = $_it['children']  ?? [];
                    $_it['is_folder'] = $_it['is_folder'] ?? 0;
                    if (!isset($_cfg[$_k])) continue;
                    $_c = $_cfg[$_k];
                    if ((int)$_c->hidden) { unset($all_nav[$_k]); continue; }
                    if ($_c->label) $_it['label']    = $_c->label;
                    if ($_c->icon)  $_it['icon']     = $_c->icon;
                    $_it['priority'] = (int)$_c->sort_order;
                }
                unset($_it);

                // Inject custom/folder items - respect permission filter for staff/contractor
                $_is_restricted = $auth_user && in_array($auth_user->role, ['staff','contractor']);
                foreach ($_nm as $_r) {
                    if ($_r->parent_key !== '') continue;
                    if (isset($all_nav[$_r->nav_key])) continue;
                    if ((int)$_r->hidden) continue;
                    if ($_is_restricted && !in_array($_r->nav_key, $always_visible) && !in_array($_r->nav_key, $permitted ?? [])) continue;
                    $all_nav[$_r->nav_key] = [
                        'label'    => $_r->label, 'url' => $_r->url ?: '#',
                        'icon'     => $_r->icon ?: 'fa-solid fa-folder',
                        'priority' => (int)$_r->sort_order, 'children' => [],
                        'is_folder'=> isset($_r->is_folder) ? (int)$_r->is_folder : 0,
                        'is_custom'=> isset($_r->is_custom) ? (int)$_r->is_custom : 0,
                    ];
                }

                // Attach children
                foreach ($_nm as $_r) {
                    if (!$_r->parent_key || !isset($all_nav[$_r->parent_key])) continue;
                    $all_nav[$_r->parent_key]['children'][] = [
                        'nav_key' => $_r->nav_key,
                        'label'   => $_r->label, 'url' => $_r->url,
                        'icon'    => $_r->icon ?: 'fa-solid fa-circle-dot',
                    ];
                    // Remove from top level - it now lives inside a section
                    if (isset($all_nav[$_r->nav_key])) unset($all_nav[$_r->nav_key]);
                }
            }
        } catch (Exception $_e) {}

        // -- Filter nav to active modules only ---------------------------------
        try {
            $_active_mods = DB::all('SELECT module_id FROM bh_modules WHERE active=1');
            $_active_ids  = array_column((array)$_active_mods, 'module_id');
            foreach ($all_nav as $_k => $_it) {
                $always = ['dashboard','clients','help','sales','finances','billing','settings'];
                if (in_array($_k, $always)) continue;
                if (!empty($_it['is_folder'])) continue;
                if (!empty($_it['is_custom'])) continue;        // custom hub links (not modules)
                if (!in_array($_k, $_active_ids)) unset($all_nav[$_k]);
            }
            // Also filter children
            foreach ($all_nav as $_k => &$_it) {
                if (empty($_it['children'])) continue;
                $_it['children'] = array_values(array_filter($_it['children'], function($child) use ($_active_ids) {
                    $ck = $child['nav_key'] ?? '';
                    if (!$ck || str_starts_with($ck, 'sep_')) return true;
                    return in_array($ck, $_active_ids);
                }));
            }
            unset($_it);
        } catch (Throwable $_e) {}

        uasort($all_nav, fn($a,$b) => ($a['priority']??50) <=> ($b['priority']??50));
        foreach ($all_nav as $item):
            $children     = $item['children'] ?? [];
            $has_children = !empty($children);
            $is_folder    = !empty($item['is_folder']);
            $item_url     = $item['url'] ?? '';
            $has_url      = $item_url && $item_url !== '#';

            // Active state
            $active = false;
            if ($has_url) {
                $active = ($current === $item_url || str_starts_with($current, $item_url.'/')) && $item_url !== '/dashboard'
                       || ($item_url === '/dashboard' && ($current === '/dashboard' || $current === '/'));
            }
            if (!$active && $has_children) {
                foreach ($children as $child) {
                    $curl = $child['url'] ?? '';
                    if ($curl && ($current === $curl || str_starts_with($current, $curl.'/'))) {
                        $active = true; break;
                    }
                }
            }
        ?>
        <?php if (str_starts_with($item['nav_key'] ?? '', 'sep_')): ?>
        <li style="margin:6px 8px;border:none;list-style:none;"><hr style="border:none;border-top:1px solid rgba(255,255,255,.15);margin:0;"></li>
        <?php elseif ($has_children || $is_folder): ?>
        <li class="has-submenu <?php echo $active ? 'active open' : ''; ?>"
            onclick="this.classList.toggle('open')" style="cursor:pointer;">
            <div class="submenu-row">
                <i class="<?php echo View::e($item['icon'] ?? 'fa-solid fa-folder'); ?>" style="margin-right:8px;font-size:16px;color:var(--pfx-sidebar-icon);flex-shrink:0;width:20px;text-align:center;"></i><span class="submenu-folder-label"><?php echo View::e($item['label']); ?></span>
                <i class="fa-solid fa-chevron-down submenu-arrow"></i>
            </div>
            <ul class="submenu">
                <?php foreach ($children as $child):
                    $curl    = $child['url'] ?? '';
                    $cactive = $curl && ($current === $curl || str_starts_with($current, $curl.'/'));
                ?>
                <li class="<?php echo $cactive ? 'active' : ''; ?>">
                    <a href="<?php echo $curl ? BH_APP_URL . $curl : '#'; ?>" onclick="event.stopPropagation()">
                        <i class="<?php echo View::e($child['icon'] ?? 'fa-solid fa-circle-dot'); ?>"></i>
                        <?php echo View::e($child['label']); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php else: ?>
        <li class="<?php echo $active ? 'active' : ''; ?>">
            <a href="<?php echo BH_APP_URL . $item_url; ?>">
                <i class="<?php echo View::e($item['icon']); ?> menu-icon"></i>
                <span class="menu-text"><?php echo View::e($item['label']); ?></span>
            </a>
        </li>
        <?php endif; ?>
        <?php endforeach; ?>

        <!-- Settings always at bottom - hidden for contractors -->
        <?php if (!Auth::isContractor()): ?>
        <li style="margin-top:auto;" class="<?php echo str_starts_with($current,'/settings') ? 'active' : ''; ?>">
            <a href="<?php echo BH_APP_URL; ?>/settings">
                <i class="fa-solid fa-gear menu-icon"></i>
                <span class="menu-text">Settings</span>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="<?php echo BH_APP_URL; ?>/logout">
                <i class="fa-solid fa-right-from-bracket menu-icon"></i>
                <span class="menu-text">Sign Out</span>
            </a>
        </li>
    </ul>

    <div style="padding:10px 10px 12px;text-align:center;font-size:10px;color:rgba(255,255,255,0.18);letter-spacing:.06em;text-transform:uppercase;">
        v<?php echo BH_VERSION; ?>
    </div>
</div>

<!-- ===================================================
     MAIN
==================================================== -->
<div id="perfex-main">

<!-- ===================================================
     TOP HEADER BAR  (sticky, inside content column)
==================================================== -->
<div id="perfex-header">
    <div class="header-inner">

        <!-- Hamburger removed -->
        <!-- Mobile logo (hidden on desktop via CSS, shown when sidebar is off-canvas) -->
        <?php
        $_hdr_logo = bh_setting('company_logo', '');
        $_hdr_name = View::e(bh_setting('company_name', BH_APP_NAME));
        ?>
        <a href="<?php echo BH_APP_URL; ?>/dashboard" class="header-mobile-logo">
            <?php if ($_hdr_logo): ?>
            <img src="<?php echo View::e($_hdr_logo); ?>" alt="<?php echo $_hdr_name; ?>"
                 style="max-height:32px;width:auto;vertical-align:middle;">
            <?php else: ?>
            <i class="fa-solid fa-shield-halved" style="margin-right:6px;color:var(--pfx-primary);"></i><?php echo $_hdr_name; ?>
            <?php endif; ?>
        </a>

        <div class="header-search" style="flex:1;max-width:480px;margin:0 16px;position:relative;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px;pointer-events:none;"></i>
            <input type="text" id="global-search-input" placeholder="Search customers, tickets, invoices..." autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" style="width:100%;height:36px;padding:0 12px 0 34px;border:2px solid #94a3b8;border-radius:8px;background:#fff;font-size:14px;color:#374151;outline:none;font-family:Inter,sans-serif;">
            <div id="global-search-results" style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:9999;max-height:420px;overflow-y:auto;"></div>
            <button id="global-search-btn" style="position:absolute;right:6px;top:50%;transform:translateY(-50%);background:var(--pfx-primary);color:#fff;border:none;border-radius:6px;padding:4px 12px;font-size:13px;font-weight:600;cursor:pointer;">Go</button>
        </div>
        <?php if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1'): ?>
        <a href="https://businessportalonline.com/free-sign-up/" target="_blank" rel="noopener" class="demo-signup-cta"
           style="margin-left:16px;display:inline-flex;align-items:center;gap:8px;background:#16a34a;color:#fff;text-decoration:none;font-weight:700;font-size:13px;padding:9px 18px;border-radius:8px;box-shadow:0 2px 6px rgba(22,163,74,.3);white-space:nowrap;font-family:Inter,sans-serif;">
            <i class="fa-solid fa-rocket"></i> Sign Up &mdash; Get Yours For Free
        </a>
        <?php endif; ?>
        <div style="margin-left:auto;display:flex;align-items:center;gap:4px;">

           <!-- Marketplace -->
            <a href="<?php echo BH_APP_URL; ?>/marketplace" title="Plugins">Plugins</a> &nbsp; &nbsp; &nbsp;

            <!-- Help -->
            <a href="<?php echo BH_APP_URL; ?>/help" class="header-btn" title="Help"><i class="fa-regular fa-circle-question"></i></a>

            <div class="header-divider"></div>

            <!-- Notifications -->
            <div class="header-dropdown">
                <button class="header-btn" id="notifBtn" title="Notifications">
                    <i class="fa-regular fa-bell"></i>
                    <?php
                    $_notif_count = 0;
                    try { $_notif_count = (int)(DB::val('SELECT COUNT(*) FROM bh_notifications WHERE user_id=? AND read_at IS NULL', [$auth_user->id ?? 0]) ?? 0); } catch(Throwable $_e) {}
                    if ($_notif_count > 0): ?>
                    <span class="notif-count"><?php echo $_notif_count > 9 ? '9+' : $_notif_count; ?></span>
                    <?php endif; ?>
                </button>
                <div class="header-dropdown-panel notif-panel" id="notifPanel">
                    <div class="notif-panel-header">
                        <h4>Notifications</h4>
                    </div>
                    <div class="notif-empty">You're all caught up </div>
                </div>
            </div>

            <div class="header-divider"></div>

            <!-- Billing status badge - hidden for contractors -->
            <?php if (!Auth::isContractor()):
            $__b = BH_MODULES . '/billing/views/header_badge.php';
            if (file_exists($__b)) require $__b;
            endif; ?>
            <?php if (!Auth::isContractor()):
                $__sb = BH_MODULES . '/billing/views/storage_banner.php';
                if (file_exists($__sb)) require $__sb;
            endif; ?>

            <!-- User chip -->
            <?php if ($auth_user): ?>
            <div class="header-dropdown">
                <a href="#" class="header-user-chip" id="userChipBtn">
                    <div class="header-user-avatar" style="background:var(--pfx-primary);">
                        <?php echo strtoupper(substr($auth_user->name, 0, 1)); ?>
                    </div>
                    <span class="header-user-name"><?php echo View::e($auth_user->name); ?></span>
                    <i class="fa-solid fa-chevron-down header-user-caret"></i>
                </a>
                <div class="header-dropdown-panel user-menu-panel" id="userMenuPanel">
                    <div class="user-menu-header">
                        <div class="u-name"><?php echo View::e($auth_user->name); ?></div>
                        <div class="u-email"><?php echo View::e($auth_user->email ?? ''); ?></div>
                    </div>
                    <a href="<?php echo BH_APP_URL; ?>/settings/users/<?php echo $auth_user->id; ?>/edit" class="user-menu-item">
                        <i class="fa-solid fa-user"></i> My Profile
                    </a>
                    <a href="<?php echo BH_APP_URL; ?>/settings" class="user-menu-item">
                        <i class="fa-solid fa-gear"></i> Settings
                    </a>
                    <a href="<?php echo BH_APP_URL; ?>/logout" class="user-menu-item u-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

    <div id="perfex-content">

        <!-- Flash messages -->
        <?php foreach ($flash ?? [] as $f): ?>
        <div class="alert alert-<?php echo View::e($f['type']); ?>" data-auto-dismiss style="margin-bottom:16px;">
            <i class="fa-solid <?php echo $f['type']==='success'?'fa-check-circle':($f['type']==='error'?'fa-circle-exclamation':'fa-info-circle'); ?>"></i>
            <?php echo View::e($f['message']); ?>
            <button class="alert-dismiss" onclick="this.closest('.alert').remove()">&times;</button>
        </div>
        <?php endforeach; ?>