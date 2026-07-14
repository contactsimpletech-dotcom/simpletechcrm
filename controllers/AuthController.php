<?php
class AuthController {

    public function showLogin(): void {
        if (!empty($_SESSION['_expired'])) {
            unset($_SESSION['_expired']);
            View::flash('info', 'Your session expired due to inactivity. Please log in again.');
        }
        if (Auth::check()) Router::redirect('/dashboard');
        // Already authenticated as a portal contact
        if (class_exists('PortalAuth')) {
            PortalAuth::start();
            if (PortalAuth::check()) Router::redirect('/portal/dashboard');
        }

        // Emergency Duo bypass — disables Duo if secret key matches
        $bypass = Request::get('duo_bypass', '');
        $secret = bh_setting('duo_bypass_key', '');
        if ($bypass && $secret && hash_equals($secret, $bypass)) {
            DB::query("UPDATE bh_settings SET `value`='0' WHERE `key`='duo_enabled'");
            View::flash('success', 'Duo MFA has been disabled. You may now log in.');
            Router::redirect('/login');
        }

        View::render('auth/login', [], true);
    }

    public function login(): void {
        Csrf::check();
        $email    = Request::email('email');
        $password = Request::post('password', '');
        $next     = Request::get('next', '/dashboard');

        // Sanitize $next — empty string or non-path values fall back to configured redirect
        if (!$next || !str_starts_with($next, '/') || str_starts_with($next, '//')) {
            $next = bh_setting('login_redirect', '/dashboard') ?: '/dashboard';
        }

        $result = Auth::attempt($email, $password);
        if ($result === "locked") {
            self::sendFailAlert("Account locked — repeated failures", $email);
            Audit::loginLocked($email);
            View::flash("error", "Account temporarily locked due to too many failed attempts. Try again in 15 minutes.");
            Router::redirect("/login?next=" . urlencode($next));
        }
        if ($result === "ok") {
            if (class_exists('DuoHelper')) DuoHelper::checkRequired(Auth::user(), $next);
            Audit::loginSuccess($email);
            if (Auth::isClient()) Router::redirect('/portal/dashboard');
            Router::redirect($next);
        } elseif ($result === 'mfa') {
            Router::redirect('/mfa?next=' . urlencode($next));
        } else {
            View::flash('error', 'Invalid email or password.');
            self::sendFailAlert('Bad password', $email);
            Audit::loginFail($email);
            Router::redirect('/login?next=' . urlencode($next));
        }
    }

    public function showMfa(): void {
        if (!Auth::mfaPending()) Router::redirect('/login');
        View::render('auth/mfa', [], true);
    }

    public function mfa(): void {
        Csrf::check();
        $uid  = Auth::mfaPending();
        if (!$uid) Router::redirect('/login');
        $code = Request::str('code');
        $next = Request::get('next', '/dashboard');

        $user = DB::one('SELECT * FROM bh_users WHERE id = ?', [$uid]);
        if ($user && TOTP::verify($user->mfa_secret, $code)) {
            Auth::login($uid);
        Audit::mfaSuccess($user->email);
            if (class_exists('DuoHelper')) DuoHelper::checkRequired(Auth::user(), $next);
            Router::redirect($next);
        } else {
            View::flash('error', 'Invalid authentication code. Please try again.');
            self::sendFailAlert('Bad MFA code', $user->email ?? 'uid:' . $uid);
            Audit::mfaFail($user->email ?? 'uid:' . $uid);
            Router::redirect('/mfa?next=' . urlencode($next));
        }
    }

    public function logout(): void {
        Auth::logout();
        Router::redirect('/login');
    }

    public function showForgot(): void {
        View::render('auth/forgot', [], true);
    }

    public function forgot(): void {
        Csrf::check();
        $email = Request::email('email');
        $user  = DB::one('SELECT id FROM bh_users WHERE email = ? AND active = 1', [$email]);
        // Always show success to avoid user enumeration
        if ($user) {
            $token = bin2hex(random_bytes(32));
            // Set expiry using MySQL's clock (NOW() + 1 hour), because the
            // validation query compares against MySQL NOW(). Using PHP's time()
            // here would break when PHP and MySQL run in different timezones
            // (e.g. PHP=America/Los_Angeles, MySQL=UTC) — the link would be
            // "expired" the moment it's created.
            DB::query(
                'INSERT INTO bh_password_resets (user_id, token, expires_at) VALUES (?, ?, NOW() + INTERVAL 1 HOUR)',
                [$user->id, $token]
            );
            $resetUrl = BH_APP_URL . '/reset?token=' . $token;
            $html = '<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;padding:20px;">'
                  . '<h2>Password Reset Request</h2>'
                  . '<p>Hello,</p>'
                  . '<p>A password reset was requested for your WebPSA account.</p>'
                  . '<p><a href="' . $resetUrl . '" style="background:#3b82f6;color:#fff;padding:12px 24px;text-decoration:none;border-radius:6px;display:inline-block;">Reset My Password</a></p>'
                  . '<p>Or copy this link: ' . $resetUrl . '</p>'
                  . '<p>This link expires in 1 hour.</p>'
                  . '<p>If you did not request this, ignore this email.</p>'
                  . '</body></html>';
            try {
                BHMailer::send($email, 'Password Reset Request', $html);
            } catch (\Throwable $e) {
                error_log('Password reset email failed: ' . $e->getMessage());
            }
        }
        View::flash('success', 'If that email exists, a reset link has been sent.');
        Router::redirect('/forgot');
    }

    public function showReset(): void {
        $token = Request::get('token', '');
        $row   = DB::one('SELECT * FROM bh_password_resets WHERE token = ? AND used = 0 AND expires_at > NOW()', [$token]);
        if (!$row) {
            View::flash('error', 'This reset link is invalid or has expired.');
            Router::redirect('/login');
        }
        View::render('auth/reset', ['token' => $token], true);
    }

    public function reset(): void {
        Csrf::check();
        $token = Request::str('token');
        $pass  = Request::post('password', '');
        $conf  = Request::post('confirm', '');
        $row   = DB::one('SELECT * FROM bh_password_resets WHERE token = ? AND used = 0 AND expires_at > NOW()', [$token]);
        if (!$row || $pass !== $conf || strlen($pass) < 8) {
            View::flash('error', 'Invalid request or passwords do not match (min 8 chars).');
            Router::redirect('/reset?token=' . $token);
        }
        DB::update('bh_users', ['password' => Auth::hash($pass)], ['id' => $row->user_id]);
        DB::update('bh_password_resets', ['used' => 1], ['id' => $row->id]);
        View::flash('success', 'Password updated. Please log in.');
        Router::redirect('/login');
    }

    // ── Security alert helper ─────────────────────────────────────────────────
    private static function sendFailAlert(string $type, string $identifier): void
    {
        $ip    = $_SERVER['HTTP_CF_CONNECTING_IP']
              ?? $_SERVER['HTTP_X_FORWARDED_FOR']
              ?? $_SERVER['REMOTE_ADDR']
              ?? 'unknown';
        $ua    = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $time  = date('Y-m-d H:i:s T');
        $host  = $_SERVER['HTTP_HOST'] ?? BH_APP_URL;

        $subject = "[WebPSA] Failed login alert — {$type}";
        $html = '<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;padding:24px;color:#111;">
<h2 style="color:#dc2626;">⚠ Failed Login Attempt</h2>
<table style="border-collapse:collapse;width:100%;max-width:520px;">
<tr><td style="padding:8px;font-weight:600;width:140px;">Type</td><td style="padding:8px;">' . htmlspecialchars($type) . '</td></tr>
<tr style="background:#f9fafb;"><td style="padding:8px;font-weight:600;">Account</td><td style="padding:8px;">' . htmlspecialchars($identifier) . '</td></tr>
<tr><td style="padding:8px;font-weight:600;">IP Address</td><td style="padding:8px;">' . htmlspecialchars($ip) . '</td></tr>
<tr style="background:#f9fafb;"><td style="padding:8px;font-weight:600;">Time</td><td style="padding:8px;">' . htmlspecialchars($time) . '</td></tr>
<tr><td style="padding:8px;font-weight:600;">Host</td><td style="padding:8px;">' . htmlspecialchars($host) . '</td></tr>
<tr style="background:#f9fafb;"><td style="padding:8px;font-weight:600;">User Agent</td><td style="padding:8px;font-size:12px;color:#6b7280;">' . htmlspecialchars($ua) . '</td></tr>
</table>
<p style="margin-top:24px;font-size:12px;color:#9ca3af;">Sent by WebPSA security monitor</p>
</body></html>';

        $alert_to = bh_setting('company_email', '') ?: bh_setting('smtp_from_email', '')
                    ?: (defined('BH_MAIL_FROM') ? BH_MAIL_FROM : '');
        if ($alert_to) {
            try {
                BHMailer::send($alert_to, $subject, $html);
            } catch (\Throwable $e) {
                error_log('[WebPSA] Failed login alert email error: ' . $e->getMessage());
            }
        }
    }
}
