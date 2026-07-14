<?php $page_title = 'Dashboard'; ?>
<div class="page-title-area">
    <h1 style="margin:0;font-size:20px;font-weight:700;">
        <i class="fa-solid fa-gauge-high" style="color:var(--pfx-primary);margin-right:8px;"></i>Dashboard
    </h1>
    <div class="page-title-actions">
        <span style="font-size:13px;color:#9ca3af;">Welcome back, <?php echo View::e($auth_user->name ?? 'User'); ?></span>
    </div>
</div>

<!-- Stat cards from modules -->
<?php if (!empty($stats)): ?>
<div class="stat-cards-row">
    <?php foreach ($stats as $s): ?>
    <a href="<?php echo View::e($s['url'] ?? '#'); ?>" style="text-decoration:none;">
        <div class="stat-card" style="border-left:3px solid <?php echo View::e($s['color'] ?? '#3b82f6'); ?>;">
            <div class="stat-card-label">
                <span><i class="<?php echo View::e($s['icon'] ?? 'fa-solid fa-circle'); ?>"
                         style="color:<?php echo View::e($s['color'] ?? '#3b82f6'); ?>;"></i>
                    <?php echo View::e($s['label']); ?>
                </span>
            </div>
            <div class="stat-card-value"><?php echo View::e($s['value']); ?></div>
            <?php if (!empty($s['sub'])): ?>
            <div class="stat-card-sub"><?php echo View::e($s['sub']); ?></div>
            <?php endif; ?>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Module widgets -->
<?php if (!empty($widgets)): ?>
    <?php foreach ($widgets as $widget): ?>
        <?php echo $widget; ?>
    <?php endforeach; ?>
<?php else: ?>
<div class="card" style="text-align:center;padding:60px 20px;">
    <i class="fa-solid fa-layer-group" style="font-size:48px;color:#e5e7eb;display:block;margin-bottom:16px;"></i>
    <h2 style="color:#374151;margin:0 0 8px;">Welcome to <?php echo View::e(BH_APP_NAME); ?></h2>
    <p style="color:#9ca3af;margin:0 0 24px;">Install modules to get started. Head to Settings &rarr; Modules to upload your first module.</p>
    <a href="<?= BH_APP_URL ?>/settings/modules" class="btn btn-primary">
        <i class="fa-solid fa-puzzle-piece" style="margin-right:6px;"></i>Install Modules
    </a>
</div>
<?php endif; ?>
