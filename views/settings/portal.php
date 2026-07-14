<?php /** Portal settings — rendered inside staff layout */ ?>

<div class="page-title-area">
    <div>
        <h1 style="margin:0;font-size:20px;font-weight:700;">
            <i class="fa-solid fa-globe" style="color:var(--pfx-primary);margin-right:8px;"></i>Client Portal Settings
        </h1>
        <p style="margin:4px 0 0;font-size:13px;color:#9ca3af;">
            Portal URL: <a href="<?= $portal_url ?>" target="_blank" style="color:var(--pfx-primary);"><?= $portal_url ?></a>
        </p>
    </div>
    <div class="page-title-actions">
        <a href="<?= BH_APP_URL ?>/portal/admin" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-chart-bar" style="margin-right:5px;"></i>Portal Admin
        </a>
    </div>
</div>

<div style="max-width:620px;">
<form method="POST" action="<?= BH_APP_URL ?>/settings/portal">
    <?= Csrf::field() ?>

    <!-- General -->
    <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-toggle-on" style="color:var(--pfx-primary);margin-right:8px;"></i>General</h3>
        </div>
        <div class="card-body" style="display:grid;gap:16px;">

            <label style="display:flex;align-items:center;gap:12px;cursor:pointer;">
                <input type="checkbox" name="portal_enabled" value="1"
                       <?= $enabled ? 'checked' : '' ?>
                       style="accent-color:var(--pfx-primary);width:16px;height:16px;">
                <div>
                    <div style="font-weight:600;font-size:14px;">Enable Client Portal</div>
                    <div style="font-size:12px;color:#9ca3af;">When disabled, clients see a maintenance page.</div>
                </div>
            </label>

            <div>
                <label class="form-label">Portal Title</label>
                <input type="text" name="portal_title" class="form-control"
                       value="<?= View::e($title) ?>" placeholder="Client Portal">
            </div>

            <div>
                <label class="form-label">Welcome Message</label>
                <textarea name="portal_welcome_msg" class="form-control" rows="2"
                          style="resize:vertical;"
                          placeholder="Welcome to your client portal."><?= View::e($welcome_msg) ?></textarea>
                <p style="margin:4px 0 0;font-size:12px;color:#9ca3af;">Shown on the dashboard after login.</p>
            </div>
        </div>
    </div>

    <!-- Available Sections -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-table-cells-large" style="color:var(--pfx-primary);margin-right:8px;"></i>Available Sections</h3>
        </div>
        <div class="card-body">
            <p style="margin:0 0 16px;font-size:13px;color:#6b7280;">
                Toggle which sections clients can see in the portal. Sections whose backing module is not installed are shown but disabled.
            </p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <?php foreach ($all_sections as $key => $cfg):
                    $is_on = in_array($key, $enabled_keys);
                    $installed = true;
                    if ($cfg['table']) {
                        try {
                            $installed = (bool)DB::val(
                                "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?",
                                [$cfg['table']]
                            );
                        } catch (\Throwable $e) { $installed = false; }
                    }
                    $border_color = ($is_on && $installed) ? $cfg['color'] : '#e5e7eb';
                    $bg_color     = ($is_on && $installed) ? $cfg['color'] . '12' : ($installed ? '#fff' : '#f9fafb');
                ?>
                <label style="display:flex;align-items:center;justify-content:space-between;
                              padding:12px 14px;border-radius:8px;cursor:<?= $installed?'pointer':'default' ?>;
                              border:2px solid <?= $border_color ?>;background:<?= $bg_color ?>;
                              opacity:<?= $installed?'1':'.5' ?>;"
                       title="<?= !$installed ? 'Module not installed — install the backing module first' : '' ?>">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:8px;background:<?= $cfg['color'] ?>1a;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid <?= $cfg['icon'] ?>" style="color:<?= $cfg['color'] ?>;font-size:15px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13px;color:#111827;"><?= View::e($cfg['label']) ?></div>
                            <?php if (!$installed): ?>
                            <div style="font-size:10px;color:#9ca3af;margin-top:1px;">Not installed</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="checkbox"
                           name="portal_sections[<?= $key ?>]" value="1"
                           <?= $is_on ? 'checked' : '' ?>
                           <?= !$installed ? 'disabled' : '' ?>
                           style="accent-color:<?= $cfg['color'] ?>;width:18px;height:18px;flex-shrink:0;"
                           onclick="this.closest('label').style.borderColor = this.checked ? '<?= $cfg['color'] ?>' : '#e5e7eb';
                                    this.closest('label').style.background  = this.checked ? '<?= $cfg['color'] ?>12' : '<?= $installed?'#fff':'#f9fafb' ?>';">
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Sticky save bar — visible regardless of scroll, glows when changes are pending -->
    <div id="portalSaveBar" style="position:sticky;bottom:0;background:#fff;border-top:1px solid #e5e7eb;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;gap:12px;margin:0 -16px -16px;border-radius:0 0 8px 8px;z-index:20;">
        <div id="portalSaveStatus" style="font-size:13px;color:#9ca3af;">No changes</div>
        <button type="submit" id="portalSaveBtn" class="btn btn-primary">
            <i class="fa-solid fa-save" style="margin-right:6px;"></i>Save Settings
        </button>
    </div>
</form>
</div>

<script>
(function () {
    const form = document.querySelector('form[action$="/settings/portal"]');
    if (!form) return;
    const status = document.getElementById('portalSaveStatus');
    const bar    = document.getElementById('portalSaveBar');
    const btn    = document.getElementById('portalSaveBtn');
    const initial = new FormData(form);
    function snapshot(fd) { const o = {}; for (const [k, v] of fd.entries()) o[k] = (o[k] ? o[k] + ',' : '') + v; return JSON.stringify(o); }
    const initialSnap = snapshot(initial);
    function check() {
        const dirty = snapshot(new FormData(form)) !== initialSnap;
        status.textContent  = dirty ? 'Unsaved changes' : 'No changes';
        status.style.color  = dirty ? '#b45309' : '#9ca3af';
        bar.style.background = dirty ? '#fffbeb' : '#fff';
        btn.style.opacity   = dirty ? '1' : '0.6';
    }
    form.addEventListener('change', check);
    form.addEventListener('input',  check);
    check();
})();
</script>
