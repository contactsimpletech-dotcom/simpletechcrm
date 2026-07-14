<?php
defined('BH_ROOT') || define('BH_ROOT', dirname(__DIR__));

// ── Load local overrides FIRST so they win over defaults below ────────────────
// If config.local.php is missing, the app isn't configured yet (no DB creds), so
// send web requests to the /install wizard, which writes that file. CLI is left
// alone, and a request already inside /install is never redirected (no loop).
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
} elseif (PHP_SAPI !== 'cli' && !headers_sent()
          && strpos($_SERVER['REQUEST_URI'] ?? '', '/install') === false) {
    $__base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($__base === '/' || $__base === '.') $__base = '';
    header('Location: ' . $__base . '/install/');
    exit;
}

// ── Database (defaults — skipped if config.local.php already defined them) ────
defined('BH_DB_HOST')    || define('BH_DB_HOST',    'localhost');
defined('BH_DB_PORT')    || define('BH_DB_PORT',    '');
defined('BH_DB_SOCKET')  || define('BH_DB_SOCKET',  '');
defined('BH_DB_NAME')    || define('BH_DB_NAME',    'perfectcrm');
defined('BH_DB_USER')    || define('BH_DB_USER',    'root');
defined('BH_DB_PASS')    || define('BH_DB_PASS',    '');
defined('BH_DB_CHARSET') || define('BH_DB_CHARSET', 'utf8mb4');

// ── App ───────────────────────────────────────────────────────────────────────
defined('BH_APP_NAME')   || define('BH_APP_NAME',   'Perfect CRM');
defined('BH_APP_URL')    || define('BH_APP_URL',     'http://localhost');
defined('BH_APP_ENV')    || define('BH_APP_ENV',     'production');
defined('BH_DEBUG')      || define('BH_DEBUG',       BH_APP_ENV === 'development');
defined('BH_VERSION')    || define('BH_VERSION', '1.1.7');
defined('BH_TIMEZONE')   || define('BH_TIMEZONE',    'America/Chicago');

// ── Base path (subfolder support) ─────────────────────────────────────────────
// Auto-detect the URL subpath the app is installed under, so it works at the
// domain root OR in any subfolder (e.g. /test2) with no manual config.
// SCRIPT_NAME is always "<base>/index.php"; dirname() gives "<base>" ('' at root).
if (!defined('BH_BASE_PATH')) {
    $__base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $__base = rtrim($__base, '/');            // '' at docroot, '/test2' in a subfolder
    if ($__base === '/' || $__base === '.') $__base = '';
    define('BH_BASE_PATH', $__base);
}

// ── Paths ─────────────────────────────────────────────────────────────────────
defined('BH_CORE')       || define('BH_CORE',    BH_ROOT . '/core');
defined('BH_VIEWS')      || define('BH_VIEWS',   BH_ROOT . '/views');
defined('BH_MODULES')    || define('BH_MODULES', BH_ROOT . '/modules');
// Storage: single-tenant install stores under the app root.
if (!defined('BH_STORAGE')) {
    $__bh_storage = BH_ROOT . '/storage';
    if (!is_dir($__bh_storage)) @mkdir($__bh_storage, 0755, true);
    define('BH_STORAGE', $__bh_storage);
    unset($__bh_storage);
}
defined('BH_CACHE')   || define('BH_CACHE',   BH_STORAGE . '/cache');
defined('BH_UPLOADS') || define('BH_UPLOADS', BH_STORAGE . '/uploads');
defined('BH_LOGS')    || define('BH_LOGS',    BH_STORAGE . '/logs');
// Ensure derived dirs exist (cheap no-op when present)
foreach ([BH_CACHE, BH_UPLOADS, BH_LOGS] as $__bh_dir) {
    if (!is_dir($__bh_dir)) @mkdir($__bh_dir, 0755, true);
}
unset($__bh_dir);
defined('BH_PUBLIC')     || define('BH_PUBLIC',  BH_ROOT   . '/public');

// Assets URL — falls back to app_url/assets if not set by local config
defined('BH_ASSETS_URL') || define('BH_ASSETS_URL', BH_APP_URL . '/assets');

// ── Security ──────────────────────────────────────────────────────────────────
defined('BH_ENCRYPTION_KEY') || define('BH_ENCRYPTION_KEY', 'CHANGE_ME_64_HEX_CHARS_000000000000000000000000000000000000000000');
defined('BH_SESSION_NAME')   || define('BH_SESSION_NAME',   'bh_sess');
defined('BH_SESSION_TTL')    || define('BH_SESSION_TTL',    28800); // 8 hours

// ── Email ─────────────────────────────────────────────────────────────────────
defined('BH_MAIL_FROM')      || define('BH_MAIL_FROM',      'noreply@localhost');
defined('BH_MAIL_FROM_NAME') || define('BH_MAIL_FROM_NAME', BH_APP_NAME);
defined('BH_MAIL_SMTP_HOST') || define('BH_MAIL_SMTP_HOST', '');
defined('BH_MAIL_SMTP_PORT') || define('BH_MAIL_SMTP_PORT', 587);
defined('BH_MAIL_SMTP_USER') || define('BH_MAIL_SMTP_USER', '');
defined('BH_MAIL_SMTP_PASS') || define('BH_MAIL_SMTP_PASS', '');

// ── Pagination ────────────────────────────────────────────────────────────────
defined('BH_PER_PAGE') || define('BH_PER_PAGE', 25);

// ── Runtime ───────────────────────────────────────────────────────────────────
date_default_timezone_set(BH_TIMEZONE);
if (BH_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '1');
}
