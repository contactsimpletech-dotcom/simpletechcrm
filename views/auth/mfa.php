<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Two-Factor Auth &mdash; <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;background:#fff;font-family:'DM Sans',sans-serif;}
.mfa-page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px 16px;background:#fff;}
.mfa-card{background:#fff;border-radius:16px;border:2px solid #0033cc;width:100%;max-width:420px;padding:40px 40px 36px;}
.mfa-icon{width:56px;height:56px;background:#fff;border:2px solid #0033cc;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:22px;color:#0033cc;}
.mfa-title{font-size:20px;font-weight:600;color:#0f172a;text-align:center;margin-bottom:6px;letter-spacing:-.01em;}
.mfa-sub{font-size:13.5px;color:#64748b;text-align:center;margin-bottom:28px;line-height:1.55;}
.mfa-alert{padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px;}
.mfa-alert-error  {background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;}
.mfa-alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;}
.mfa-alert-info   {background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;}
.mfa-label{display:block;font-size:11px;font-weight:600;color:#94a3b8;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;text-align:center;}
.mfa-input{width:100%;background:#fff;border:2px solid #0033cc;border-radius:10px;padding:14px 16px;font-size:28px;font-weight:600;font-family:'DM Sans',sans-serif;letter-spacing:14px;text-align:center;color:#0f172a;outline:none;transition:border-color .15s;-webkit-appearance:none;margin-bottom:20px;}
.mfa-input:focus{border-color:#0022aa;box-shadow:0 0 0 3px rgba(0,51,204,.08);}
.mfa-input::placeholder{color:#cbd5e1;letter-spacing:8px;font-size:22px;font-weight:400;}
.mfa-submit{width:100%;padding:13px;background:#0033cc;border:none;border-radius:9px;color:#fff;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:600;cursor:pointer;transition:background .15s;display:flex;align-items:center;justify-content:center;gap:8px;}
.mfa-submit:hover{background:#0022aa;}
.mfa-submit:disabled{background:#93c5fd;cursor:not-allowed;}
hr{border:none;border-top:1px solid #e2e8f0;margin:22px 0;}
.mfa-hint{display:flex;align-items:center;gap:8px;padding:11px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:20px;}
.mfa-hint i{font-size:14px;color:#94a3b8;flex-shrink:0;}
.mfa-hint-text{font-size:12px;color:#64748b;line-height:1.5;}
.mfa-back{display:flex;align-items:center;justify-content:center;gap:6px;font-size:13px;color:#94a3b8;text-decoration:none;transition:color .15s;}
.mfa-back:hover{color:#0033cc;}
</style>
</head>
<body>
<div class="mfa-page">
<div class="mfa-card">

    <div class="mfa-icon">
        <i class="fa-solid fa-mobile-screen-button"></i>
    </div>

    <h1 class="mfa-title">Two-Factor Authentication</h1>
    <p class="mfa-sub">Open your authenticator app and enter<br>the 6-digit verification code below.</p>

    <?php foreach ($flash ?? [] as $f): ?>
    <div class="mfa-alert mfa-alert-<?php echo View::e($f['type']); ?>">
        <?php if ($f['type']==='error'): ?><i class="fa-solid fa-circle-exclamation"></i>
        <?php elseif ($f['type']==='success'): ?><i class="fa-solid fa-circle-check"></i>
        <?php else: ?><i class="fa-solid fa-circle-info"></i><?php endif; ?>
        <?php echo View::e($f['message']); ?>
    </div>
    <?php endforeach; ?>

    <form method="POST" action="<?php echo BH_APP_URL; ?>/mfa<?php echo isset($_GET['next']) ? '?next='.urlencode($_GET['next']) : ''; ?>" id="mfa-form">
        <?php echo Csrf::field(); ?>
        <label class="mfa-label">Verification Code</label>
        <input type="text" name="code" id="mfa-code" class="mfa-input"
               placeholder="&bull; &bull; &bull; &bull; &bull; &bull;"
               maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
               autocomplete="one-time-code" autofocus
               readonly onfocus="this.removeAttribute('readonly')">
        <button type="submit" class="mfa-submit" id="mfa-btn">
            <i class="fa-solid fa-shield-halved"></i> Verify &amp; Sign In
        </button>
    </form>

    <hr>

    <div class="mfa-hint">
        <i class="fa-solid fa-circle-question"></i>
        <span class="mfa-hint-text">Use Google Authenticator, Authy, or any TOTP app. Codes refresh every 30 seconds.</span>
    </div>

    <a href="<?php echo BH_APP_URL; ?>/login" class="mfa-back">
        <i class="fa-solid fa-arrow-left" style="font-size:12px;"></i> Back to login
    </a>

</div>
</div>
<script>
document.getElementById('mfa-code').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g,'').slice(0,6);
    if (this.value.length === 6) {
        document.getElementById('mfa-btn').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
        document.getElementById('mfa-btn').disabled = true;
        document.getElementById('mfa-form').submit();
    }
});
</script>
</body>
</html>
