<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Supplier Registration &mdash; <?php echo View::e(BH_APP_NAME); ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{background:#f3f4f6;font-family:'DM Sans',system-ui,sans-serif;color:#111827;}
.page{min-height:100vh;padding:40px 16px 60px;display:flex;flex-direction:column;align-items:center;}
.card{width:100%;max-width:700px;background:#fff;border:2px solid #000;border-radius:18px;padding:44px 48px;box-shadow:0 20px 50px rgba(0,0,0,.07);}
.brand{display:flex;align-items:center;gap:10px;margin-bottom:32px;}
.brand-icon{width:38px;height:38px;border-radius:9px;background:#2563eb;display:flex;align-items:center;justify-content:center;}
.brand-name{font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:#111827;}
h1{font-family:'Syne',sans-serif;font-size:26px;font-weight:800;color:#111827;margin-bottom:6px;}
.sub{font-size:14px;color:#6b7280;margin-bottom:28px;line-height:1.55;}
.section-label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#6b7280;margin:24px 0 14px;padding-bottom:8px;border-bottom:1px solid #e5e7eb;}
.row{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.field{display:flex;flex-direction:column;margin-bottom:16px;}
.field:last-child{margin-bottom:0;}
label{font-size:11.5px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#4b5563;margin-bottom:7px;}
label .req{color:#dc2626;margin-left:2px;}
input,select,textarea{width:100%;background:#fff;border:1.5px solid #d1d5db;border-radius:8px;padding:11px 14px;font-size:14px;color:#111827;font-family:inherit;outline:none;transition:border-color .15s,box-shadow .15s;}
input:focus,select:focus,textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12);}
input::placeholder,textarea::placeholder{color:#9ca3af;}
textarea{resize:vertical;min-height:90px;}
.alert{padding:11px 14px;border-radius:8px;font-size:13.5px;margin-bottom:20px;display:flex;align-items:flex-start;gap:9px;border:1.5px solid;}
.alert-error{background:#fef2f2;border-color:#fecaca;color:#991b1b;}
.alert-success{background:#f0fdf4;border-color:#bbf7d0;color:#166534;}
.alert-info{background:#eff6ff;border-color:#bfdbfe;color:#1e40af;}
.btn{width:100%;padding:13px;border:none;border-radius:8px;background:#2563eb;color:#fff;font-family:'Syne',sans-serif;font-size:15px;font-weight:700;letter-spacing:.04em;cursor:pointer;margin-top:28px;transition:background .15s;}
.btn:hover{background:#1d4ed8;}
.footer{margin-top:22px;font-size:12.5px;color:#9ca3af;text-align:center;}
@media(max-width:580px){.card{padding:32px 20px;}.row{grid-template-columns:1fr;gap:0;}}
</style>
</head>
<body>
<div class="page">
<div class="card">

    <div class="brand">
        <?php $__logo = function_exists('bh_setting') ? bh_setting('company_logo','') : ''; ?>
        <?php if ($__logo): ?>
        <img src="<?php echo View::e($__logo); ?>" alt="<?php echo View::e(BH_APP_NAME); ?>" style="max-height:44px;max-width:200px;object-fit:contain;">
        <?php else: ?>
        <div class="brand-icon">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M9 1L2 4V9C2 12.87 5.02 16.48 9 17.5C12.98 16.48 16 12.87 16 9V4L9 1Z" fill="white" fill-opacity=".95"/>
            </svg>
        </div>
        <span class="brand-name"><?php echo View::e(BH_APP_NAME); ?></span>
        <?php endif; ?>
    </div>

    <h1>Supplier Registration</h1>
    <p class="sub">Complete this form to apply as a corporate supplier or partner. Our team will review your application and reach out within 2–3 business days.</p>

    <?php foreach ($flash ?? [] as $f): ?>
    <div class="alert alert-<?php echo View::e($f['type']); ?>">
        <?php if ($f['type']==='error'): ?><i class="fa-solid fa-circle-exclamation"></i>
        <?php elseif ($f['type']==='success'): ?><i class="fa-solid fa-circle-check"></i>
        <?php else: ?><i class="fa-solid fa-circle-info"></i><?php endif; ?>
        <span><?php echo View::e($f['message']); ?></span>
    </div>
    <?php endforeach; ?>

    <form method="POST" action="<?= BH_APP_URL ?>/admin/supplier/corporate/profile">
        <?php echo Csrf::field(); ?>

        <div class="section-label"><i class="fa-solid fa-building" style="margin-right:6px;"></i>Company Information</div>

        <div class="row">
            <div class="field">
                <label>Company Name <span class="req">*</span></label>
                <input type="text" name="company_name" required placeholder="Acme Corp" maxlength="255">
            </div>
            <div class="field">
                <label>Business Type <span class="req">*</span></label>
                <select name="business_type" required>
                    <option value="">— Select —</option>
                    <option value="Corporation">Corporation (Corp / Inc)</option>
                    <option value="LLC">LLC</option>
                    <option value="Partnership">Partnership</option>
                    <option value="Sole Proprietor">Sole Proprietor</option>
                    <option value="Non-Profit">Non-Profit</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Website</label>
                <input type="url" name="website" placeholder="https://example.com" maxlength="255">
            </div>
            <div class="field">
                <label>Tax ID / EIN</label>
                <input type="text" name="tax_id" placeholder="XX-XXXXXXX" maxlength="50">
            </div>
        </div>

        <div class="field">
            <label>Street Address</label>
            <input type="text" name="address" placeholder="123 Main St" maxlength="255">
        </div>

        <div class="row">
            <div class="field">
                <label>City</label>
                <input type="text" name="city" maxlength="100">
            </div>
            <div class="field" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="display:flex;flex-direction:column;">
                    <label>State</label>
                    <input type="text" name="state" maxlength="50" placeholder="TX">
                </div>
                <div style="display:flex;flex-direction:column;">
                    <label>ZIP</label>
                    <input type="text" name="zip" maxlength="20" placeholder="75001">
                </div>
            </div>
        </div>

        <div class="section-label"><i class="fa-solid fa-user" style="margin-right:6px;"></i>Primary Contact</div>

        <div class="row">
            <div class="field">
                <label>Contact Name <span class="req">*</span></label>
                <input type="text" name="contact_name" required placeholder="Jane Smith" maxlength="255">
            </div>
            <div class="field">
                <label>Phone</label>
                <input type="tel" name="phone" placeholder="+1 (555) 000-0000" maxlength="80">
            </div>
        </div>

        <div class="field">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" name="email" required placeholder="jane@example.com" maxlength="255">
        </div>

        <div class="section-label"><i class="fa-solid fa-briefcase" style="margin-right:6px;"></i>Services &amp; Details</div>

        <div class="field">
            <label>Services / Products Offered</label>
            <textarea name="services_offered" placeholder="Describe what your company provides…"></textarea>
        </div>

        <div class="field">
            <label>Additional Notes</label>
            <textarea name="notes" placeholder="Anything else you'd like us to know…"></textarea>
        </div>

        <button type="submit" class="btn"><i class="fa-solid fa-paper-plane" style="margin-right:8px;"></i>Submit Application</button>
    </form>

</div>
<p class="footer">&copy; <?php echo date('Y'); ?> <?php echo View::e(BH_APP_NAME); ?> &mdash; Supplier Portal</p>
</div>
</body>
</html>
