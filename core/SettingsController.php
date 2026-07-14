<?php
class SettingsController {

    public function index(): void {
        Auth::requireAdmin();
        $settings = [];
        foreach (DB::all('SELECT * FROM bh_settings') as $r) $settings[$r->key] = $r->value;
        View::render('settings/index', compact('settings'));
    }

    public function save(): void {
        Auth::requireAdmin(); Csrf::check();
        $fields = ['company_name','company_email','company_phone','company_address',
                   'invoice_prefix','default_currency','default_tax_rate','mileage_rate',
                   'sidebar_color','accent_color','sidebar_text_color','submenu_color','submenu_text_color','timezone','login_redirect','nav_font_size','nav_sub_font_size','sidebar_theme','login_bg_color'];
        foreach ($fields as $k) {
            $val = Request::str($k);
            DB::query('INSERT INTO bh_settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?',
                [$k, $val, $val]);
        }

        // Logo upload
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') {
            unset($_FILES['company_logo']); $_POST['remove_logo'] = '';
        }
        if (!empty($_FILES['company_logo']['name']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['company_logo'];
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png','jpg','jpeg','gif','svg','webp'])) {
                $upload_dir = BH_STORAGE . '/logos';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                $filename = 'company_logo.' . $ext;
                $dest     = $upload_dir . '/' . $filename;
                if (bh_safe_upload($file['tmp_name'], $dest, 'company_logo')) {
                    $logo_url = BH_APP_URL . '/logos/' . $filename . '?v=' . time();
                    DB::query('INSERT INTO bh_settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?',
                        ['company_logo', $logo_url, $logo_url]);
                }
            }
        }

        // Remove logo if requested
        if (!empty($_POST['remove_logo'])) {
            DB::query('DELETE FROM bh_settings WHERE `key` = ?', ['company_logo']);
            // Attempt to remove files
            foreach (['png','jpg','jpeg','gif','svg','webp'] as $ext) {
                $f = BH_STORAGE . '/logos/company_logo.' . $ext;
                if (file_exists($f)) @unlink($f);
            }
        }

        // Login background image upload (served via /logos like the company logo)
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') {
            unset($_FILES['login_bg']); $_POST['remove_login_bg'] = '';
        }
        if (!empty($_FILES['login_bg']['name']) && $_FILES['login_bg']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['login_bg'];
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png','jpg','jpeg','gif','webp'])) {
                $upload_dir = BH_STORAGE . '/logos';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                $filename = 'login_bg.' . $ext;
                $dest     = $upload_dir . '/' . $filename;
                if (bh_safe_upload($file['tmp_name'], $dest, 'login_bg')) {
                    // clear any other-extension copy so the newest wins
                    foreach (['png','jpg','jpeg','gif','webp'] as $e2) {
                        if ($e2 !== $ext) { $old = $upload_dir . '/login_bg.' . $e2; if (file_exists($old)) @unlink($old); }
                    }
                    $bg_url = BH_APP_URL . '/logos/' . $filename . '?v=' . time();
                    DB::query('INSERT INTO bh_settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?',
                        ['login_bg_image', $bg_url, $bg_url]);
                }
            }
        }

        // Remove login background if requested
        if (!empty($_POST['remove_login_bg'])) {
            DB::query('DELETE FROM bh_settings WHERE `key` = ?', ['login_bg_image']);
            foreach (['png','jpg','jpeg','gif','webp'] as $ext) {
                $f = BH_STORAGE . '/logos/login_bg.' . $ext;
                if (file_exists($f)) @unlink($f);
            }
        }

        // Apply timezone immediately for this request
        $tz = Request::str('timezone');
        if ($tz) @date_default_timezone_set($tz);

        View::flashSuccess('Settings saved.');
        Router::redirect('/settings');
    }

    // ── Module Manager ─────────────────────────────────────────────────────────

    public function modules(): void {
        Auth::requireAdmin();
        $modules = ModuleLoader::all();
        View::render('settings/modules', compact('modules'));
    }

    public function moduleUpload(): void {
        Auth::requireAdmin(); Csrf::check();

        $file = Request::file('module_zip');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            View::flashError('Upload failed. Please try again.');
            Router::redirect('/settings/modules');
        }

        if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'zip') {
            View::flashError('Only .zip files are accepted.');
            Router::redirect('/settings/modules');
        }

        // Move to staging
        $staging = BH_STORAGE . '/modules-staging';
        if (!is_dir($staging)) mkdir($staging, 0755, true);
        $dest = $staging . '/' . uniqid('mod_') . '.zip';
        move_uploaded_file($file['tmp_name'], $dest);

        $result = ModuleLoader::install($dest);
        @unlink($dest);

        if (!$result['ok']) {
            View::flashError('Install failed: ' . $result['error']);
        } else {
            $msg = 'Module "' . $result['name'] . '" installed successfully.';
            if (!empty($result['deps_warn'])) {
                $msg .= ' Warning: recommended modules not active: ' . implode(', ', $result['deps_warn']) . '.';
            }
            View::flashSuccess($msg);
        }
        Router::redirect('/settings/modules');
    }

    public function moduleToggle(): void {
        Auth::requireAdmin(); Csrf::check();
        $id     = Request::str('module_id');
        $active = Request::bool('active');
        ModuleLoader::toggle($id, $active);
        View::success(['active' => $active]);
    }

    public function moduleUninstall(): void {
        Auth::requireAdmin(); Csrf::check();
        $id = Request::str('module_id');
        if (!$id) View::error('No module ID.');
        ModuleLoader::uninstall($id);
        View::flashSuccess('Module uninstalled. Files kept on disk.');
        Router::redirect('/settings/modules');
    }

    // ── User Management ────────────────────────────────────────────────────────

    public function users(): void {
        Auth::requireAdmin();
        $users = DB::all('SELECT * FROM bh_users ORDER BY name ASC');
        View::render('settings/users', compact('users'));
    }

    public function userCreate(): void {
        Auth::requireAdmin();
        View::render('settings/user-form', ['user' => null]);
    }

    public function userStore(): void {
        Auth::requireAdmin(); Csrf::check();
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') {
            View::flashError('User management is disabled in the demo.');
            Router::redirect('/settings/users'); return;
        }
        $email = Request::email('email');
        if (DB::val('SELECT id FROM bh_users WHERE email = ?', [$email])) {
            View::flashError('A user with that email already exists.');
            Router::redirect('/settings/users/add');
        }
        DB::insert('bh_users', [
            'name'     => Request::str('name'),
            'email'    => $email,
            'password' => Auth::hash(Request::post('password', '')),
            'role'     => Request::str('role') ?: 'staff',
            'phone'    => Request::str('phone'),
            'title'    => Request::str('title'),
            'active'   => 1,
        ]);
        View::flashSuccess('User created.');
        Router::redirect('/settings/users');
    }

    public function userEdit(array $p): void {
        Auth::requireAdmin();
        $user = DB::one('SELECT * FROM bh_users WHERE id = ?', [$p['id']]);
        if (!$user) Router::abort(404);
        View::render('settings/user-form', compact('user'));
    }

    public function userUpdate(array $p): void {
        Auth::requireAdmin(); Csrf::check();
        $data = ['name'=>Request::str('name'),'email'=>Request::email('email'),
                 'role'=>Request::str('role'),'phone'=>Request::str('phone'),
                 'title'=>Request::str('title'),'active'=>Request::bool('active')?1:0];
        $pass = Request::post('password','');
        if ($pass) $data['password'] = Auth::hash($pass);
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') { unset($data['email']); unset($data['password']); }
        DB::update('bh_users', $data, ['id' => $p['id']]);
        View::flashSuccess('User updated.');
        Router::redirect('/settings/users');
    }

    // ── MFA Setup ──────────────────────────────────────────────────────────────

    public function mfaSetup(): void {
        Auth::requireLogin();
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') {
            View::flashError('MFA is disabled in the demo environment.');
            Router::redirect('/settings'); return;
        }
        $user   = Auth::user();
        $secret = $user->mfa_secret ?: TOTP::secret();
        $url    = TOTP::otpauth($user->email, $secret);
        $qr     = TOTP::qr($url);
        View::render('settings/mfa', compact('secret', 'qr', 'user'));
    }

    public function mfaSave(): void {
        Auth::requireLogin(); Csrf::check();
        if (function_exists('bh_setting') && bh_setting('demo_mode','0') === '1') {
            View::flashError('MFA is disabled in the demo environment.');
            Router::redirect('/settings'); return;
        }
        $secret = Request::str('secret');
        $code   = Request::str('code');
        if (!TOTP::verify($secret, $code)) {
            View::flashError('Invalid code. Please try again.');
            Router::redirect('/settings/mfa');
        }
        DB::update('bh_users', ['mfa_secret' => $secret], ['id' => Auth::id()]);
        View::flashSuccess('Two-factor authentication enabled.');
        Router::redirect('/settings/mfa');
    }

    public function mfaDisable(): void {
        Auth::requireLogin(); Csrf::check();
        DB::update('bh_users', ['mfa_secret' => null], ['id' => Auth::id()]);
        View::flashSuccess('Two-factor authentication disabled.');
        Router::redirect('/settings/mfa');
    }

    public function userDelete(array $params): void {
        Auth::requireAdmin();
        Csrf::check();
        $id = (int)($params['id'] ?? 0);
        if (!$id) Router::redirect('/settings/users');
        if ($id === Auth::id()) {
            View::flash('error', 'You cannot delete your own account.');
            Router::redirect('/settings/users');
        }
        $user = DB::one('SELECT id FROM bh_users WHERE id = ?', [$id]);
        if (!$user) {
            View::flash('error', 'User not found.');
            Router::redirect('/settings/users');
        }
        DB::delete('bh_users', ['id' => $id]);
        View::flash('success', 'User deleted.');
        Router::redirect('/settings/users');
    }

    public function serveUpload(array $p): void {
        $dir  = preg_replace('/[^a-z0-9_\-]/', '', $p['dir'] ?? '');
        $file = basename($p['file'] ?? '');
        if (!$file) Router::abort(404);
        $path = $dir ? BH_UPLOADS . '/' . $dir . '/' . $file : BH_STORAGE . '/logos/' . $file;
        if (!file_exists($path)) Router::abort(404);
        // Derive MIME from extension — mime_content_type() needs the fileinfo
        // extension, which isn't always enabled. Fall back only if available.
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map  = ['png'=>'image/png','jpg'=>'image/jpeg','jpeg'=>'image/jpeg',
                 'gif'=>'image/gif','svg'=>'image/svg+xml','webp'=>'image/webp',
                 'ico'=>'image/x-icon','bmp'=>'image/bmp'];
        if (isset($map[$ext])) {
            $mime = $map[$ext];
        } elseif (function_exists('mime_content_type')) {
            $mime = mime_content_type($path) ?: 'application/octet-stream';
        } else {
            $mime = 'application/octet-stream';
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: public, max-age=86400');
        readfile($path);
        exit;
    }
}