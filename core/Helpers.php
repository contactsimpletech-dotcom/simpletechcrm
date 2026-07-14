<?php
class Csrf {
    public static function token(): string {
        if (empty($_SESSION['bh_csrf'])) $_SESSION['bh_csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['bh_csrf'];
    }
    public static function field(): string {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }
    public static function verify(): bool {
        $t = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return hash_equals(self::token(), $t);
    }
    public static function check(): void {
        if (Request::isPost() && !self::verify()) {
            View::error('Invalid CSRF token.', 403);
        }
    }
}

class Cache {
    public static function set(string $key, mixed $val, int $ttl = 300): void {
        file_put_contents(self::path($key), json_encode(['exp' => time()+$ttl, 'val' => $val]), LOCK_EX);
    }
    public static function get(string $key): mixed {
        $f = self::path($key);
        if (!file_exists($f)) return null;
        $d = json_decode(file_get_contents($f), true);
        if (!$d || $d['exp'] < time()) { @unlink($f); return null; }
        return $d['val'];
    }
    public static function forget(string $key): void { @unlink(self::path($key)); }
    public static function flush(): void { foreach (glob(BH_CACHE.'/*.json') as $f) @unlink($f); }
    public static function remember(string $key, int $ttl, callable $cb): mixed {
        $v = self::get($key);
        if ($v !== null) return $v;
        $v = $cb();
        if ($v !== null) self::set($key, $v, $ttl);
        return $v;
    }
    private static function path(string $key): string { return BH_CACHE . '/' . md5($key) . '.json'; }
}

class Encrypt {
    private static function key(): string {
        $h = BH_ENCRYPTION_KEY;
        return strlen($h) === 64 ? hex2bin($h) : hash('sha256', $h, true);
    }
    public static function encrypt(string $plain): string {
        if ($plain === '') return '';
        $key = self::key(); $iv = random_bytes(12); $tag = '';
        $enc = openssl_encrypt($plain, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, '', 16);
        return base64_encode('GCM:'.$iv.$tag.$enc);
    }
    public static function decrypt(string $cipher): string {
        if ($cipher === '') return '';
        $raw = base64_decode($cipher);
        if (!str_starts_with($raw, 'GCM:')) return '';
        $raw  = substr($raw, 4);
        $iv   = substr($raw, 0, 12);
        $tag  = substr($raw, 12, 16);
        $enc  = substr($raw, 28);
        $out  = openssl_decrypt($enc, 'aes-256-gcm', self::key(), OPENSSL_RAW_DATA, $iv, $tag);
        return $out !== false ? $out : '';
    }
}

class TOTP {
    public static function secret(int $len = 16): string {
        $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $r = random_bytes($len); $s = '';
        for ($i = 0; $i < $len; $i++) $s .= $c[ord($r[$i]) & 31];
        return $s;
    }
    public static function code(string $secret, ?int $ts = null): string {
        $ts ??= (int)floor(time()/30);
        $key = self::decode($secret);
        $msg = pack('N*',0).pack('N*',$ts);
        $hm  = hash_hmac('sha1', $msg, $key, true);
        $off = ord($hm[19]) & 0xf;
        $n   = ((ord($hm[$off])&0x7f)<<24)|((ord($hm[$off+1])&0xff)<<16)|((ord($hm[$off+2])&0xff)<<8)|(ord($hm[$off+3])&0xff);
        return str_pad($n % 1000000, 6, '0', STR_PAD_LEFT);
    }
    public static function verify(string $secret, string $code, int $drift = 1): bool {
        $code = preg_replace('/\s+/','',$code);
        $ts   = (int)floor(time()/30);
        for ($i=-$drift; $i<=$drift; $i++) if (hash_equals(self::code($secret,$ts+$i),$code)) return true;
        return false;
    }
    public static function otpauth(string $email, string $secret, string $issuer = BH_APP_NAME): string {
        return 'otpauth://totp/'.rawurlencode($issuer.':'.$email).'?'.http_build_query(['secret'=>$secret,'issuer'=>$issuer,'digits'=>6,'period'=>30]);
    }
    public static function qr(string $url): string {
        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.rawurlencode($url);
    }
    private static function decode(string $s): string {
        $map = array_flip(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'));
        $s   = strtoupper($s); $out = ''; $buf = 0; $bits = 0;
        for ($i=0,$l=strlen($s);$i<$l;$i++) {
            if (!isset($map[$s[$i]])) continue;
            $buf   = ($buf<<5)|$map[$s[$i]]; $bits += 5;
            if ($bits>=8) { $bits-=8; $out .= chr(($buf>>$bits)&0xFF); }
        }
        return $out;
    }
}

class Http {
    public static function get(string $url, array $headers = [], int $timeout = 20): array {
        return self::req('GET', $url, null, $headers, $timeout);
    }
    public static function post(string $url, mixed $body = null, array $headers = [], int $timeout = 20): array {
        return self::req('POST', $url, $body, $headers, $timeout);
    }
    public static function patch(string $url, mixed $body = null, array $headers = []): array {
        return self::req('PATCH', $url, $body, $headers);
    }
    public static function delete(string $url, array $headers = []): array {
        return self::req('DELETE', $url, null, $headers);
    }
    private static function req(string $method, string $url, mixed $body, array $headers, int $timeout = 20): array {
        $ch   = curl_init($url);
        $hdrs = $headers;
        if ($body !== null) {
            $payload = is_array($body) ? json_encode($body) : $body;
            if (is_array($body) && !in_array('Content-Type: application/x-www-form-urlencoded', $headers)) {
                $hdrs[] = 'Content-Type: application/json';
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_HTTPHEADER     => $hdrs,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);
        return ['code'=>$code,'body'=>json_decode($body,true)??$body,'error'=>$err];
    }
}


/**
 * Drop-in replacement for move_uploaded_file() that adds content moderation.
 * 
 * Usage: same as move_uploaded_file() — returns bool.
 *   if (!bh_safe_upload($_FILES['x']['tmp_name'], $dest)) {
 *       // Either the move failed OR the content was blocked.
 *   }
 *
 * Context (which module) is auto-detected from the calling file path.
 */
if (!function_exists('bh_safe_upload')) {
    function bh_safe_upload(string $tmp, string $dest, ?string $context = null, ?int $related_id = null): bool {
        if ($context === null) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            if (!empty($bt[0]['file']) && preg_match('#/modules/([^/]+)/#', $bt[0]['file'], $m)) {
                $context = $m[1] . '_attachment';
            } else {
                $context = 'unknown';
            }
        }
        if (!move_uploaded_file($tmp, $dest)) return false;
        if (class_exists('Moderation')) {
            try {
                $v = Moderation::check($dest, $context, $related_id);
                if ($v->blocked) {
                    @unlink($dest);
                    error_log("[moderation] BLOCKED [$context]: {$v->reasons}");
                    if (class_exists('View') && method_exists('View', 'flashError')) {
                        View::flashError('Upload rejected: content policy violation.');
                    }
                    return false;
                }
            } catch (Throwable $e) {
                error_log("[moderation] check failed: " . $e->getMessage());
                // Fail-open: allow the upload
            }
        }
        return true;
    }
}

