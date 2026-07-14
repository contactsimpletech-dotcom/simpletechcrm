<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reset Password &mdash; <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo BH_ASSETS_URL; ?>/css/app.css">
</head>
<body>
<div class="bh-login-wrap">
<div class="bh-login-card">
    <div class="bh-login-header">
        <div style="font-size:28px;margin-bottom:8px;"><i class="fa-solid fa-lock-open"></i></div>
        <h1>New Password</h1>
        <p>Choose a strong password (min. 8 characters)</p>
    </div>
    <div class="bh-login-body">
        <?php foreach ($flash ?? [] as $f): ?>
        <div class="alert alert-<?php echo View::e($f['type']); ?>"><?php echo View::e($f['message']); ?></div>
        <?php endforeach; ?>
        <form method="POST" action="<?= BH_APP_URL ?>/reset">
            <?php echo Csrf::field(); ?>
            <input type="hidden" name="token" value="<?php echo View::e($token); ?>">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" minlength="8" required autofocus placeholder="Minimum 8 characters">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm" class="form-control" minlength="8" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:11px;">Set New Password</button>
        </form>
    </div>
</div>
</div>
</body>
</html>
