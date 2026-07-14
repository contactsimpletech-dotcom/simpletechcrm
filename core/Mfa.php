<?php
class Mfa
{
    /**
     * Enforce MFA for all logged-in users.
     * - Has mfa_secret: existing Auth::attempt() flow already handles verify via /mfa
     * - No mfa_secret: force setup at /settings/mfa
     */
    public static function enforce(): void
    {
        if (!Auth::check()) return;
        // DEMO: never force MFA enrollment in a demo tenant.
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') return;
        if (self::isExempt()) return;

        $user = Auth::user();
        if (!$user) return;

        // No MFA set up yet — only force enrollment if the admin has turned on
        // mandatory MFA (Settings). Self-hosted default is optional MFA: users
        // set it up themselves from Settings whenever they choose.
        $mfa_required = (function_exists('bh_setting') && bh_setting('mfa_required','0') === '1');
        if ($mfa_required && empty($user->mfa_secret)) {
            Router::redirect('/settings/mfa');
        }
        // Has mfa_secret: Auth::attempt() already gated login through /mfa verify
        // Nothing more needed here
    }

    public static function isExempt(): bool
    {
        $uri    = '/' . trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        $exempt = [
            '/auth/login',
            '/login',
            '/logout',
            '/mfa',
            '/forgot',
            '/reset',
            '/settings/mfa',
            '/settings/mfa-enable',
            '/settings/mfa/disable',
            '/portal',
            '/contracts/sign',
        ];

        // Always exempt any POST to an MFA route
        if ($method === 'POST' && str_starts_with($uri, '/settings/mfa')) {
            return true;
        }

        foreach ($exempt as $e) {
            if ($uri === $e
                || str_starts_with($uri, $e . '/')
                || str_starts_with($uri, $e . '?')
            ) {
                return true;
            }
        }
        return false;
    }
}
