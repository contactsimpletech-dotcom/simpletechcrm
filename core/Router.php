<?php
class Router {
    private static array $routes = [];

    public static function get(string $p, callable|string $h): void  { self::add('GET', $p, $h); }
    public static function post(string $p, callable|string $h): void { self::add('POST', $p, $h); }
    public static function any(string $p, callable|string $h): void  { self::add('ANY', $p, $h); }

    private static function add(string $method, string $pattern, callable|string $handler): void {
        self::$routes[] = [$method, $pattern, $handler];
    }

    public static function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = '/' . trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

        // Subfolder support: strip the install base path so routes registered as
        // "/dashboard" still match when the app lives at e.g. /test2/dashboard.
        $base = defined('BH_BASE_PATH') ? BH_BASE_PATH : '';
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
            if ($uri === '' || $uri[0] !== '/') $uri = '/' . $uri;
        }

        // Billing soft-block — redirect to /billing/expired if subscription expired.
        // Only applies to logged-in non-client users; clients never see it.
        if (class_exists('Auth') && Auth::check() && !Auth::isClient() && class_exists('Billing')) {
            try { Billing::enforceAccess($uri); } catch (\Throwable $e) {}
        }


        foreach (self::$routes as [$rm, $pattern, $handler]) {
            if ($rm !== 'ANY' && $rm !== $method) continue;
            $regex = '#^' . preg_replace('#/:([a-z_]+)#', '/(?P<$1>[^/]+)', $pattern) . '$#i';
            if (!preg_match($regex, $uri, $m)) continue;
            $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
            if (is_callable($handler)) {
                $handler($params);
            } else {
                [$cls, $method_name] = explode('@', $handler);
                (new $cls)->$method_name($params);
            }
            return;
        }

        // Unknown route → Marketplace. If a logged-in staff/admin hits a path with
        // no registered route, it's almost always a module they don't have active,
        // so send them to the Marketplace instead of a dead 404. Guards:
        //  - GET only (form POSTs to a dead module shouldn't bounce)
        //  - staff/admin only (clients go to their portal via abort() below)
        //  - first segment must be a bare slug (skips favicon.ico, *.css, etc.)
        //  - skip system paths, and only redirect if Marketplace is actually
        //    installed (a /marketplace route exists) — prevents any redirect loop.
        if ($method === 'GET' && class_exists('Auth') && Auth::check() && !Auth::isClient()) {
            $seg  = strtolower(explode('/', trim($uri, '/'))[0] ?? '');
            $skip = ['', 'marketplace', 'assets', 'logos', 'uploads', 'api', 'favicon'];
            if (preg_match('/^[a-z0-9_-]+$/', $seg) && !in_array($seg, $skip, true)) {
                $hasMarket = false;
                foreach (self::$routes as $r) {
                    if (isset($r[1]) && strncmp($r[1], '/marketplace', 12) === 0) { $hasMarket = true; break; }
                }
                if ($hasMarket) { self::redirect('/marketplace'); }
            }
        }

        self::abort(404);
    }

    public static function redirect(string $url, int $code = 302): never {
        if (!str_starts_with($url, 'http')) $url = BH_APP_URL . $url;
        http_response_code($code);
        header('Location: ' . $url);
        exit;
    }

    public static function abort(int $code, string $msg = ''): never {
        if (class_exists('Auth') && Auth::isClient()) { self::redirect('/portal/dashboard'); }
        http_response_code($code);
        $msgs = [403 => 'Forbidden', 404 => 'Page Not Found', 500 => 'Server Error'];
        View::render('errors/error', ['code' => $code, 'message' => $msg ?: ($msgs[$code] ?? 'Error')], true);
        exit;
    }

    public static function url(string $path = ''): string {
        return BH_APP_URL . '/' . ltrim($path, '/');
    }
}