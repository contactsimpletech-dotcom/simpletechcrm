<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sign In &mdash; <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;background:#f3f4f6;font-family:'DM Sans',system-ui,sans-serif;color:#111827;}

.bhl-page{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px 16px;}
.bhl-footer{margin-top:20px;font-size:13px;color:#9ca3af;}
.bhl-footer a{color:#6b7280;text-decoration:none;}
.bhl-footer a:hover{color:#2563eb;text-decoration:underline;}
.bhl-role-note{font-size:11px;color:#9ca3af;margin-top:2px;}

/* ── Landscape login card ────────────────────────────────── */
.bhl-card{
    width:100%;max-width:720px;
    background:#ffffff;
    border:2px solid #000000;
    border-radius:18px;
    padding:44px 56px;
    box-shadow:0 20px 50px rgba(0,0,0,.08);
}

/* Brand row */
.bhl-brand{display:flex;align-items:center;gap:10px;margin-bottom:30px;}
.bhl-brand-icon{width:38px;height:38px;border-radius:9px;background:#2563eb;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.bhl-brand-name{font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:#111827;letter-spacing:.01em;}

.bhl-headline{font-size:26px;font-weight:800;line-height:1.15;margin:0 0 6px;color:#111827;letter-spacing:-.01em;}
.bhl-subline{font-size:14px;margin:0 0 26px;color:#4b5563;}

/* Alerts (flash messages) */
.bhl-alert{padding:11px 14px;border-radius:8px;font-size:13.5px;margin-bottom:18px;display:flex;align-items:center;gap:8px;border:1.5px solid;}
.bhl-alert-error{background:#fef2f2;border-color:#fecaca;color:#991b1b;}
.bhl-alert-success{background:#f0fdf4;border-color:#bbf7d0;color:#166534;}
.bhl-alert-info{background:#eff6ff;border-color:#bfdbfe;color:#1e40af;}

/* Role buttons */
.bhl-role-row{display:flex;flex-direction:column;gap:10px;margin-bottom:24px;}
.bhl-role-btn{flex:1;padding:14px 18px;border-radius:10px;border:1.5px solid #d1d5db;background:#ffffff;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:12px;text-decoration:none;}
.bhl-role-btn:hover{border-color:#93c5fd;}
.bhl-role-btn.active{border-color:#2563eb;background:#eff6ff;}
.bhl-role-btn i{font-size:22px;color:#6b7280;flex-shrink:0;}
.bhl-role-btn.active i{color:#2563eb;}
.bhl-role-btn .col{display:flex;flex-direction:column;min-width:0;}
.bhl-role-btn .col strong{font-size:13.5px;font-weight:700;color:#374151;}
.bhl-role-btn .col small{font-size:11.5px;color:#6b7280;margin-top:1px;}
.bhl-role-btn.active .col strong{color:#1e40af;}
.bhl-role-btn.active .col small{color:#2563eb;}

/* Demo prefill banner */
.bhl-demo{background:#eff6ff;border:1.5px solid #bfdbfe;color:#1e40af;border-radius:8px;padding:10px 14px;margin-bottom:18px;font-size:13px;text-align:center;}

/* Form: email + password side-by-side on wide, stacked on mobile */
.bhl-field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:22px;}
.bhl-field{display:flex;flex-direction:column;}
.bhl-label{display:block;font-size:11px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:#4b5563;margin-bottom:7px;}
.bhl-label-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;}
.bhl-input{width:100%;background:#ffffff;border:1.5px solid #d1d5db;border-radius:8px;padding:12px 14px;font-size:14px;color:#111827;font-family:inherit;outline:none;transition:border-color .15s,box-shadow .15s;-webkit-appearance:none;}
.bhl-input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.15);}
.bhl-input::placeholder{color:#9ca3af;}
.bhl-forgot{font-size:12px;color:#2563eb;text-decoration:none;font-weight:600;}
.bhl-forgot:hover{text-decoration:underline;}

/* Submit + MFA hint */
.bhl-submit{width:100%;padding:13px;border:none;border-radius:8px;background:#2563eb;color:#fff;font-family:'Syne',sans-serif;font-size:15px;font-weight:700;letter-spacing:.04em;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:background .15s;}
.bhl-submit:hover{background:#1d4ed8;}
.bhl-mfa-hint{display:flex;align-items:center;gap:7px;margin-top:14px;padding:10px 12px;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:7px;font-size:12.5px;color:#1e40af;}

/* Customer portal redirect block */
.bhl-portal-block{text-align:center;}
.bhl-portal-desc{font-size:14px;color:#4b5563;margin-bottom:20px;line-height:1.6;}
.bhl-portal-btn{display:block;width:100%;padding:13px;background:#2563eb;border-radius:8px;color:#fff;font-family:'Syne',sans-serif;font-size:15px;font-weight:700;letter-spacing:.04em;text-decoration:none;text-align:center;transition:background .15s;}
.bhl-portal-btn:hover{background:#1d4ed8;color:#fff;}

/* Mobile collapse: stack the form row, narrow padding */
@media(max-width:600px){
    .bhl-card{padding:32px 24px;}
    .bhl-role-row{flex-direction:column;}
    .bhl-field-row{grid-template-columns:1fr;gap:18px;}
}

/* Visibility helpers for role switch */
#bhl-staff-form,#bhl-portal-block{display:none;}
#bhl-staff-form.active,#bhl-portal-block.active{display:block;}
</style>
</head>
<body>
<?php
$__login_bg    = function_exists('bh_setting') ? bh_setting('login_bg_image','') : '';
$__login_color = function_exists('bh_setting') ? bh_setting('login_bg_color','')  : '';
$__login_logo  = function_exists('bh_setting') ? bh_setting('company_logo','')     : '';
$__page_style  = '';
if ($__login_bg !== '') {
    $__page_style = "background-image:url('".htmlspecialchars($__login_bg, ENT_QUOTES)."');background-size:cover;background-position:center;background-repeat:no-repeat;";
    if ($__login_color !== '') { $__page_style .= "background-color:".htmlspecialchars($__login_color, ENT_QUOTES).";"; }
} elseif ($__login_color !== '') {
    $__page_style = "background-color:".htmlspecialchars($__login_color, ENT_QUOTES).";";
}
?>
<div class="bhl-page"<?php echo $__page_style ? ' style="'.$__page_style.'"' : ''; ?>>
<div class="bhl-card">

    <div class="bhl-brand"<?php echo $__login_logo !== '' ? ' style="justify-content:center;"' : ''; ?>>
        <?php if ($__login_logo !== ''): ?>
        <img src="<?php echo View::e($__login_logo); ?>" alt="<?php echo View::e(BH_APP_NAME); ?>" style="max-height:46px;max-width:210px;object-fit:contain;">
        <?php else: ?>
        <div class="bhl-brand-icon">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M9 1L2 4V9C2 12.87 5.02 16.48 9 17.5C12.98 16.48 16 12.87 16 9V4L9 1Z" fill="white" fill-opacity="0.95"/>
            </svg>
        </div>
        <span class="bhl-brand-name"><?php echo View::e(BH_APP_NAME); ?></span>
        <?php endif; ?>
    </div>

    <h1 class="bhl-headline">Welcome back.</h1>
    <p class="bhl-subline">Sign in to continue to your workspace.</p>

    <?php foreach ($flash ?? [] as $f): ?>
    <div class="bhl-alert bhl-alert-<?php echo View::e($f['type']); ?>">
        <?php if ($f['type']==='error'): ?><i class="fa-solid fa-circle-exclamation"></i>
        <?php elseif ($f['type']==='success'): ?><i class="fa-solid fa-circle-check"></i>
        <?php else: ?><i class="fa-solid fa-circle-info"></i>
        <?php endif; ?>
        <?php echo View::e($f['message']); ?>
    </div>
    <?php endforeach; ?>

    <?php $__mobile_installed = defined('BH_MODULES') && is_dir(BH_MODULES . '/mobile'); ?>
    <div class="bhl-role-row">
        <a href="#" class="bhl-role-btn active" id="bhl-btn-admin" onclick="bhlChoose('admin');return false;">
            <i class="fa-solid fa-user-shield"></i>
            <div class="col">
                <strong>Admin / Staff</strong>
                <small>Internal login</small>
            </div>
        </a>
        <a href="#" class="bhl-role-btn" id="bhl-btn-customer" onclick="bhlChoose('customer');return false;">
            <i class="fa-solid fa-building-user"></i>
            <div class="col">
                <strong>Customer</strong>
                <small>Client portal</small>
            </div>
        </a>
        <a href="#" class="bhl-role-btn" id="bhl-btn-mobile" onclick="bhlChoose('mobile');return false;">
            <i class="fa-solid fa-mobile-screen-button"></i>
            <div class="col">
                <strong>Mobile</strong>
                <small>Admin mobile app</small>
                <?php if (!$__mobile_installed): ?><span class="bhl-role-note">Requires Mobile Module</span><?php endif; ?>
            </div>
        </a>
    </div>

    <?php
        $__demo = (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1');
        $__demo_email = $__demo ? bh_setting('demo_staff_email', '') : '';
        $__demo_pass  = $__demo ? bh_setting('demo_staff_pass',  '') : '';
    ?>
    <?php if ($__demo): ?>
    <div class="bhl-demo">
        Demo environment — credentials pre-filled. Resets hourly. Just click Sign In.
    </div>
    <?php endif; ?>

    <div id="bhl-staff-form" class="active">
        <form id="bhl-form" method="POST" action="<?php echo BH_APP_URL; ?>/login<?php echo isset($_GET['next'])&&$_GET['next']?'?next='.urlencode($_GET['next']):''; ?>">
            <?php echo Csrf::field(); ?>
            <div class="bhl-field-row">
                <div class="bhl-field">
                    <label class="bhl-label">Email address</label>
                    <input type="email" name="email" class="bhl-input"
                           placeholder="you@company.com"
                           value="<?php echo View::e($__demo_email ?: Request::get('email','')); ?>"
                           required autofocus autocomplete="username">
                </div>
                <div class="bhl-field">
                    <div class="bhl-label-row">
                        <label class="bhl-label" style="margin:0;">Password</label>
                        <a href="<?php echo BH_APP_URL; ?>/forgot" class="bhl-forgot">Forgot password?</a>
                    </div>
                    <input type="password" name="password" class="bhl-input"
                           placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                           value="<?php echo View::e($__demo_pass); ?>"
                           required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="bhl-submit">
                Sign In <i class="fa-solid fa-arrow-right"></i>
            </button>
            <?php if (bh_setting('mfa_enabled', 0)): ?>
            <div class="bhl-mfa-hint">
                <i class="fa-solid fa-mobile-screen-button" style="font-size:13px;flex-shrink:0;"></i>
                MFA verification will be required on next step
            </div>
            <?php endif; ?>
        </form>
    </div>

    <div id="bhl-portal-block" class="bhl-portal-block">
        <p class="bhl-portal-desc">Sign in to view your invoices, tickets, proposals, and more.</p>
        <a href="<?php echo BH_APP_URL; ?>/portal" class="bhl-portal-btn">
            Go to Client Portal <i class="fa-solid fa-arrow-right" style="margin-left:6px;"></i>
        </a>
    </div>

</div>
<div class="bhl-footer">
    <a href="https://perfect-crm.com" target="_blank" rel="noopener noreferrer" title="The most powerful CRM and PSA System for Businesses">Perfect CRM</a>
</div>
</div>

<script>
var BHL_BASE = <?php echo json_encode(BH_APP_URL); ?>;
var bhlFormDefault = (function(){ var f=document.getElementById('bhl-form'); return f?f.getAttribute('action'):''; })();
function bhlChoose(role){
    var staff = (role==='admin' || role==='mobile');
    document.getElementById('bhl-btn-admin').classList.toggle('active',role==='admin');
    document.getElementById('bhl-btn-customer').classList.toggle('active',role==='customer');
    var mb=document.getElementById('bhl-btn-mobile'); if(mb) mb.classList.toggle('active',role==='mobile');
    document.getElementById('bhl-staff-form').classList.toggle('active',staff);
    document.getElementById('bhl-portal-block').classList.toggle('active',role==='customer');
    var f=document.getElementById('bhl-form');
    if(f){ f.setAttribute('action', role==='mobile' ? (BHL_BASE + '/login?next=' + encodeURIComponent('/mobile')) : bhlFormDefault); }
}
<?php if(!empty($flash)&&$flash[0]['type']==='info'): ?>bhlChoose('customer');<?php endif; ?>
</script>
</body>
</html>