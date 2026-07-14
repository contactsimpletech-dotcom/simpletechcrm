<?php $page_title = 'Modules'; ?>
<div class="page-title-area">
    <div>
        <h1 style="margin:0;font-size:20px;font-weight:700;">
            <i class="fa-solid fa-puzzle-piece" style="color:var(--pfx-primary);margin-right:8px;"></i>Modules
        </h1>
        <p style="margin:4px 0 0;font-size:13px;color:#9ca3af;">Install, enable, and manage platform modules</p>
    </div>
</div>

<!-- Upload zone -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-upload" style="margin-right:7px;color:#3b82f6;"></i>Install New Module</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BH_APP_URL ?>/settings/modules/upload" enctype="multipart/form-data" id="module-upload-form">
            <?php echo Csrf::field(); ?>
            <div class="bh-upload-zone" id="upload-zone" onclick="document.getElementById('module_zip').click()">
                <div class="upload-icon"><i class="fa-solid fa-file-zipper"></i></div>
                <p style="margin:8px 0 4px;font-weight:600;color:#374151;">Drop a module .zip here or click to browse</p>
                <p style="margin:0;font-size:12px;color:#9ca3af;" id="upload-filename">Only .zip files are accepted</p>
                <input type="file" name="module_zip" id="module_zip" accept=".zip"
                       style="display:none;" onchange="document.getElementById('upload-filename').textContent=this.files[0]?.name||'Only .zip files accepted'">
            </div>
            <div style="margin-top:14px;display:flex;align-items:center;gap:10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-upload" style="margin-right:6px;"></i>Upload &amp; Install
                </button>
                <span style="font-size:12px;color:#9ca3af;">The module will be installed and activated immediately.</span>
            </div>
        </form>
    </div>
</div>

<!-- Installed modules -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa-solid fa-list" style="margin-right:7px;"></i>Installed Modules</h3>
        <span style="font-size:13px;color:#9ca3af;"><?php echo count($modules); ?> installed</span>
    </div>
    <?php if (empty($modules)): ?>
    <div class="card-body" style="text-align:center;padding:48px;color:#9ca3af;">
        <i class="fa-solid fa-box-open" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
        <p style="margin:0;">No modules installed yet. Upload a module zip above to get started.</p>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
    <table class="perfex-table">
        <thead>
            <tr>
                <th>Module</th>
                <th>Version</th>
                <th>Author</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($modules as $m): ?>
        <tr>
            <td>
                <div style="font-weight:600;font-size:13px;color:#111827;"><?php echo View::e($m->name); ?></div>
                <div style="font-size:12px;color:#9ca3af;margin-top:2px;"><?php echo View::e($m->module_id); ?></div>
                <?php if ($m->description): ?>
                <div style="font-size:12px;color:#6b7280;margin-top:2px;"><?php echo View::e($m->description); ?></div>
                <?php endif; ?>
            </td>
            <td style="font-size:13px;color:#6b7280;"><?php echo View::e($m->version); ?></td>
            <td style="font-size:13px;color:#6b7280;"><?php echo View::e($m->author ?: '—'); ?></td>
            <td>
                <label class="bh-toggle" title="<?php echo $m->active ? 'Disable' : 'Enable'; ?>">
                    <input type="checkbox" class="bh-module-toggle"
                           data-module="<?php echo View::e($m->module_id); ?>"
                           data-csrf="<?php echo Csrf::token(); ?>"
                           <?php echo $m->active ? 'checked' : ''; ?>>
                    <span class="bh-toggle-slider"></span>
                </label>
            </td>
            <td style="text-align:right;">
                <form method="POST" action="<?= BH_APP_URL ?>/settings/modules/uninstall" style="display:inline;"
                      onsubmit="return confirm('Uninstall <?php echo View::e(addslashes($m->name)); ?>? This will remove the database tables. Module files stay on disk.')">
                    <?php echo Csrf::field(); ?>
                    <input type="hidden" name="module_id" value="<?php echo View::e($m->module_id); ?>">
                    <button type="submit" class="btn btn-secondary btn-sm" style="color:#dc2626;border-color:#fecaca;">
                        <i class="fa-solid fa-trash"></i> Uninstall
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>
