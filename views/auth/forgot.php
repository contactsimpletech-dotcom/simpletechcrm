<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Forgot Password &mdash; <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?php echo BH_ASSETS_URL; ?>/css/app.css">
</head>
<body>
<div class="bh-login-wrap">
<div class="bh-login-card">
    <div class="bh-login-header">
        <div style="font-size:28px;margin-bottom:8px;"><i class="fa-solid fa-key"></i></div>
        <h1>Reset Password</h1>
        <p>Enter your email to receive a reset link</p>
    </div>
    <div class="bh-login-body">
        <?php foreach ($flash ?? [] as $f): ?>
        <div class="alert alert-<?php echo View::e($f['type']); ?>"><?php echo View::e($f['message']); ?></div>
        <?php endforeach; ?>
        <form method="POST" action="<?= BH_APP_URL ?>/forgot">
            <?php echo Csrf::field(); ?>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@company.com" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:11px;">Send Reset Link</button>
        </form>
        <p style="text-align:center;margin-top:16px;font-size:13px;">
            <a href="<?= BH_APP_URL ?>/login" style="color:#9ca3af;">&larr; Back to login</a>
        </p>
    </div>
</div>
</div>
</body>
</html>
