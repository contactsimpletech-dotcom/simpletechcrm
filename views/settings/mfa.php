<?php $page_title = 'Two-Factor Authentication'; ?>
<div class="page-title-area">
    <h1 style="margin:0;font-size:20px;font-weight:700;">
        <i class="fa-solid fa-lock" style="color:var(--pfx-primary);margin-right:8px;"></i>Two-Factor Authentication
    </h1>
</div>

<div style="max-width:540px;">
<?php if ($user->mfa_secret): ?>
<div class="card">
    <div class="card-body" style="text-align:center;padding:32px;">
        <div style="width:64px;height:64px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fa-solid fa-check" style="font-size:28px;color:#16a34a;"></i>
        </div>
        <h2 style="margin:0 0 8px;color:#111827;">MFA is Active</h2>
        <p style="color:#6b7280;margin:0 0 24px;">Your account is protected with an authenticator app.</p>
        <form method="POST" action="<?= BH_APP_URL ?>/settings/mfa/disable"
              onsubmit="return confirm('Disable two-factor authentication? This will make your account less secure.')">
            <?php echo Csrf::field(); ?>
            <button type="submit" class="btn btn-secondary" style="color:#dc2626;border-color:#fecaca;">
                <i class="fa-solid fa-lock-open" style="margin-right:6px;"></i>Disable MFA
            </button>
        </form>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-header"><h3 class="card-title">Set Up Two-Factor Authentication</h3></div>
    <div class="card-body">
        <?php
        $mfa_flash = View::popFlash();
        $mfa_error = false;
        if (!empty($mfa_flash)):
            foreach ($mfa_flash as $f):
                $mfa_error = $mfa_error || ($f['type'] === 'error');
        ?>
        <div class="alert alert-<?= htmlspecialchars($f['type']) ?>"
             style="margin-bottom:16px;">
            <?= htmlspecialchars($f['message']) ?>
        </div>
        <?php endforeach; endif; ?>

        <?php if ($mfa_error): ?>
        <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;
                    padding:14px 16px;margin-bottom:20px;display:flex;gap:10px;
                    align-items:flex-start;">
            <i class="fa-solid fa-triangle-exclamation"
               style="color:#d97706;font-size:16px;margin-top:2px;flex-shrink:0;"></i>
            <div>
                <div style="font-weight:600;font-size:13px;color:#92400e;margin-bottom:3px;">
                    Code didn't work? Try these steps:
                </div>
                <ol style="margin:0;padding-left:18px;font-size:13px;
                           color:#78350f;line-height:1.8;">
                    <li>Make sure your phone's time is set to <strong>automatic</strong>
                        (Settings → General → Date &amp; Time → Set Automatically).</li>
                    <li><strong>Rescan the QR code</strong> — delete the old entry from
                        your authenticator app first, then scan again below.</li>
                    <li>If you can't scan, tap <em>"Enter a setup key"</em> in your
                        authenticator and type the key shown manually.</li>
                    <li>Wait for a fresh 6-digit code to appear, then enter it quickly.</li>
                </ol>
            </div>
        </div>
        <?php endif; ?>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div style="display:flex;gap:12px;">
                <div style="width:28px;height:28px;border-radius:50%;background:#3b82f6;color:#fff;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">1</div>
                <div>
                    <div style="font-weight:600;font-size:14px;margin-bottom:4px;">Install an authenticator app</div>
                    <p style="margin:0;font-size:13px;color:#6b7280;">Download <strong>Google Authenticator</strong> or <strong>Authy</strong> on your phone.</p>
                </div>
            </div>
            <div style="display:flex;gap:12px;">
                <div style="width:28px;height:28px;border-radius:50%;background:#3b82f6;color:#fff;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">2</div>
                <div>
                    <div style="font-weight:600;font-size:14px;margin-bottom:8px;">Scan this QR code</div>
                    <img src="<?php echo View::e($qr); ?>" alt="QR Code" style="border:4px solid #f3f4f6;border-radius:8px;display:block;">
                    <div style="margin-top:10px;background:#f9fafb;border:1px dashed #d1d5db;border-radius:6px;padding:8px 12px;font-family:monospace;font-size:13px;letter-spacing:2px;color:#374151;word-break:break-all;">
                        <?php echo View::e($secret); ?>
                    </div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">Can't scan? Enter the key above manually.</div>
                </div>
            </div>
            <div style="display:flex;gap:12px;">
                <div style="width:28px;height:28px;border-radius:50%;background:#3b82f6;color:#fff;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">3</div>
                <div style="width:100%;">
                    <div style="font-weight:600;font-size:14px;margin-bottom:8px;">Enter the 6-digit code to confirm</div>
                    <form method="POST" action="<?= BH_APP_URL ?>/settings/mfa">
                        <?php echo Csrf::field(); ?>
                        <input type="hidden" name="secret" value="<?php echo View::e($secret); ?>">
                        <div style="display:flex;gap:8px;">
                            <input type="text" name="code" class="form-control" placeholder="000000" maxlength="6" inputmode="numeric" autocomplete="one-time-code" style="font-size:20px;letter-spacing:6px;text-align:center;max-width:160px;" autofocus autocomplete="one-time-code">
                            <button type="submit" class="btn btn-primary">Verify &amp; Enable</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
</div>
