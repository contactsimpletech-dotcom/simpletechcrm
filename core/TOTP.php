<?php
class TOTP
{
    private static function tfa(): \RobThree\Auth\TwoFactorAuth
    {
        require_once BH_ROOT . '/vendor/autoload.php';
        $issuer = bh_setting('app_name') ?: 'WebPSA';
        return new \RobThree\Auth\TwoFactorAuth($issuer);
    }

    public static function secret(): string
    {
        return self::tfa()->createSecret();
    }

    public static function otpauth(string $email, string $secret): string
    {
        $issuer = bh_setting('app_name') ?: 'WebPSA';
        return 'otpauth://totp/' . rawurlencode($issuer . ':' . $email)
             . '?secret=' . $secret
             . '&issuer=' . rawurlencode($issuer)
             . '&digits=6&period=30';
    }

    public static function qr(string $otpauthUrl): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . rawurlencode($otpauthUrl);
    }

    public static function verify(string $secret, string $code): bool
    {
        $code = preg_replace('/\D/', '', $code);
        if (strlen($code) !== 6) return false;
        return self::tfa()->verifyCode($secret, $code, 2);
    }

    public static function generateSecret(): string { return self::secret(); }
    public static function getQrUri(string $label, string $secret): string {
        return self::qr(self::otpauth($label, $secret));
    }
}
