<?php
/**
 * Blackhawk CRM Installer — single-tenant / cPanel edition.
 * Walks through: requirements → DB credentials → admin account → install.
 * Writes ../config.local.php, runs base schema + every module's install.sql,
 * registers & activates modules, seeds settings, creates the admin user.
 *
 * Delete this /install folder after a successful install.
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', '1');
session_start();

define('APP_ROOT', dirname(__DIR__));
define('CFG_FILE', APP_ROOT . '/config/config.local.php');
define('SQL_DIR',  __DIR__ . '/sql');

$step = $_GET['step'] ?? 'welcome';

// Already installed? guard.
if (file_exists(CFG_FILE) && $step !== 'done' && empty($_SESSION['installing'])) {
    $step = 'already';
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES); }
function gen_key(){ return bin2hex(random_bytes(32)); } // 64 hex chars

/* ---------- requirement checks ---------- */
function requirements(): array {
    $r = [];
    $r[] = ['PHP 8.1+', version_compare(PHP_VERSION,'8.1.0','>='), PHP_VERSION];
    $ext_ok = function($name){
        if (extension_loaded($name)) return true;
        // cPanel often ships native-driver variants
        $alts = ['pdo_mysql'=>['mysqlnd','nd_pdo_mysql','pdo'],'mysqli'=>['nd_mysqli','mysqlnd']];
        foreach (($alts[$name] ?? []) as $a) if (extension_loaded($a)) return true;
        // pdo_mysql truly present if PDO lists mysql driver
        if ($name==='pdo_mysql' && class_exists('PDO') && in_array('mysql', PDO::getAvailableDrivers())) return true;
        return false;
    };
    foreach (['pdo_mysql','mbstring','openssl','json','curl','gd','zip'] as $ext)
        $r[] = ["ext: $ext", $ext_ok($ext), $ext_ok($ext)?'loaded':'missing'];
    $cfgdir=APP_ROOT.'/config'; $r[] = ['config/ writable', is_writable($cfgdir), is_writable($cfgdir)?'ok':'chmod 755 config/'];
    $stor = APP_ROOT.'/storage';
    $r[] = ['storage/ writable', (is_dir($stor)?is_writable($stor):is_writable(APP_ROOT)), 'needed for uploads'];
    return $r;
}

/* ---------- the actual install ---------- */
function do_install(array $in, array &$log): bool {
    @unlink(__DIR__.'/schema-errors.log');
    // 1. connect — supports socket/port; auto-falls back localhost->127.0.0.1
    //    ([2002] "No such file or directory" = the localhost unix socket isn't where PDO expects).
    $opts = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY=>true];
    $mk_dsn = function(string $host, string $port, string $sock) use ($in): string {
        if ($sock !== '') return "mysql:unix_socket={$sock};dbname={$in['db_name']};charset=utf8mb4";
        $d = "mysql:host={$host};dbname={$in['db_name']};charset=utf8mb4";
        if ($port !== '') $d .= ";port={$port}";
        return $d;
    };
    $host = $in['db_host']; $port = (string)($in['db_port'] ?? ''); $sock = (string)($in['db_socket'] ?? '');
    // Try host as given, then 127.0.0.1 (TCP), then common unix-socket paths.
    $cands = [['h'=>($host ?: 'localhost'),'p'=>$port,'s'=>$sock]];
    if ($sock === '') {
        if (in_array(strtolower($host), ['localhost',''], true)) $cands[] = ['h'=>'127.0.0.1','p'=>$port,'s'=>''];
        foreach (['/var/run/mysqld/mysqld.sock','/run/mysqld/mysqld.sock','/var/lib/mysql/mysql.sock','/tmp/mysql.sock','/var/run/mysql/mysql.sock','/opt/lampp/var/mysql/mysql.sock'] as $sp) {
            if (@file_exists($sp)) $cands[] = ['h'=>'','p'=>'','s'=>$sp];
        }
    }
    $pdo = null; $lastErr = '';
    foreach ($cands as $c) {
        try { $pdo = new PDO($mk_dsn($c['h'], $c['p'], $c['s']), $in['db_user'], $in['db_pass'], $opts);
              $host = ($c['s'] !== '' ? '' : $c['h']); $port = $c['p']; $sock = $c['s']; break; }
        catch (Throwable $e) { $lastErr = $e->getMessage(); }
    }
    if (!$pdo) {
        $log[] = "DB connection failed: {$lastErr} — tried 'localhost', '127.0.0.1', and common socket paths. "
               . "Confirm MySQL/MariaDB is running (e.g. systemctl status mariadb) and that the DB user + database exist. "
               . "For a custom socket or port, fill the DB socket / DB port fields.";
        return false;
    }
    // persist the connection details that actually worked, so config.local.php matches
    $in['db_host']=$host; $in['db_port']=$port; $in['db_socket']=$sock;
    $log[] = "Connected to database '{$in['db_name']}'".($sock ? " via socket {$sock}" : " (host {$host})").".";
    try { $pdo->exec("SET SESSION sql_mode=''"); } catch (Throwable $e) {}
    try { $pdo->exec("SET NAMES utf8mb4"); } catch (Throwable $e) {}

    // 2. base schema
    $schema_path = SQL_DIR.'/base_schema.sql';
    if (!is_readable($schema_path)) { $log[]="FATAL: cannot read $schema_path (missing or unreadable)."; return false; }
    $base = file_get_contents($schema_path);
    if ($base===false || strlen($base) < 1000) { $log[]="FATAL: base_schema.sql empty or too small (".strlen($base)." bytes)."; return false; }
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $made=0; $errs=0; $firstErr='';
    $base_clean = preg_replace('!/\*.*?\*/!s','',$base);
    $base_clean = preg_replace('/^--.*$/m','',$base_clean);
    foreach (array_filter(array_map('trim', explode(";\n",$base_clean))) as $stmt) {
        if ($stmt==='') continue;
        try { $pdo->exec($stmt); if (stripos($stmt,'CREATE TABLE')===0) $made++; }
        catch (Throwable $e){
            $errs++;
            // capture table name + full error to a log file for diagnosis
            preg_match('/CREATE TABLE `?(\w+)`?/i',$stmt,$mm);
            $tname=$mm[1]??'(non-create)';
            @file_put_contents(__DIR__.'/schema-errors.log',
                "[$tname] ".$e->getMessage()."\n", FILE_APPEND);
            if($firstErr===''){$firstErr=substr($e->getMessage(),0,200)." | table: $tname";}
        }
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    $log[]="Base schema: created $made tables, $errs statement errors.".($firstErr?" First error: $firstErr":"");
    if ($made < 50) { $log[]="FATAL: only $made tables created — schema did not load correctly. Aborting."; return false; }

    // Modules that ship DISABLED by default (admin can enable in Module Manager).
    $disabled_default = ['agents','tax-ca','orders'];

    // 3. per-module install.sql + register in bh_modules
    $mods_dir = APP_ROOT.'/modules'; $count=0; $sort=0;
    foreach (scandir($mods_dir) as $m) {
        if ($m[0]==='.'||!is_dir("$mods_dir/$m")) continue;
        $mf = "$mods_dir/$m/module.json"; if (!file_exists($mf)) continue;
        $j = json_decode(file_get_contents($mf), true); if (!$j) continue;
        // run module install.sql if present
        $sql="$mods_dir/$m/install.sql";
        if (file_exists($sql)) {
            try { run_sql($pdo, file_get_contents($sql), true); }
            catch (Throwable $e){ $log[]="  [warn] {$m} install.sql: ".$e->getMessage(); }
        }
        // register module (with a retry that flushes any lingering result set)
        $sort+=10;
        $regParams=[$j['id']??$m, $j['name']??$m, $j['description']??'', $j['version']??'1.0.0', $j['author']??'', $sort];
        $_active = in_array($m, $disabled_default, true) ? 0 : 1;
        $regSql="INSERT INTO bh_modules (module_id,name,description,version,author,active,sort_order)
                VALUES (?,?,?,?,?,$_active,?) ON DUPLICATE KEY UPDATE active=$_active, version=VALUES(version)";
        try {
            $st=$pdo->prepare($regSql); $st->execute($regParams); $st->closeCursor(); $count++;
        } catch (Throwable $e){
            // flush connection and retry once (handles error 2014 from prior module SQL)
            try { @$pdo->query('SELECT 1')->closeCursor(); } catch (Throwable $e2){}
            try { $st=$pdo->prepare($regSql); $st->execute($regParams); $st->closeCursor(); $count++; }
            catch (Throwable $e3){ $log[]="  [warn] register {$m}: ".$e3->getMessage(); }
        }
    }
    $log[]="Registered & activated {$count} modules.";

    // 4. seed baseline settings
    $settings = [
        'company_name'=>$in['app_name'], 'app_name'=>$in['app_name'],
        'timezone'=>$in['tz'], 'currency'=>'USD', 'date_format'=>'Y-m-d',
        'moderation_enabled'=>'0', 'demo_mode'=>'0', 'mfa_required'=>'0',
    ];
    $ss=$pdo->prepare("INSERT INTO bh_settings (`key`,`value`) VALUES (?,?)
        ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)");
    foreach ($settings as $k=>$v){ try{ $ss->execute([$k,$v]); }catch(Throwable $e){} }
    $log[]="Seeded baseline settings.";

    // 4b. curate the navigation menu (mirror a clean MSP menu)
    $menu_sql = SQL_DIR.'/menu_blackhawk.sql';
    if (is_readable($menu_sql)) {
        try { run_sql($pdo, file_get_contents($menu_sql), true); $log[]="Applied curated navigation menu."; }
        catch (Throwable $e){ $log[]="  [warn] menu setup: ".$e->getMessage(); }
    }

    // 5. admin user
    try {
        $hash=password_hash($in['admin_pass'], PASSWORD_BCRYPT, ['cost'=>12]);
        $au=$pdo->prepare("INSERT INTO bh_users (name,email,password,role,active)
            VALUES (?,?,?,'admin',1) ON DUPLICATE KEY UPDATE password=VALUES(password), role='admin', active=1");
        $au->execute([$in['admin_name'], $in['admin_email'], $hash]);
        $log[]="Created admin user: {$in['admin_email']}";
    } catch (Throwable $e){ $log[]="Admin create error: ".$e->getMessage(); return false; }

    // 6. write config.local.php
    $tpl = file_get_contents(APP_ROOT.'/config.local.php.template');
    $repl = [
        '__DB_HOST__'=>$in['db_host'], '__DB_PORT__'=>$in['db_port'], '__DB_SOCKET__'=>$in['db_socket'],
        '__DB_NAME__'=>$in['db_name'], '__DB_USER__'=>$in['db_user'], '__DB_PASS__'=>$in['db_pass'],
        '__APP_NAME__'=>$in['app_name'], '__APP_URL__'=>$in['app_url'], '__TIMEZONE__'=>$in['tz'],
        '__ENC_KEY__'=>gen_key(), '__MAIL_FROM__'=>'noreply@'.parse_url($in['app_url'],PHP_URL_HOST),
    ];
    $out=strtr($tpl,$repl);
    if (@file_put_contents(CFG_FILE,$out)===false){ $log[]="Could not write config.local.php — check permissions."; return false; }
    @chmod(CFG_FILE,0640);
    $log[]="Wrote config.local.php.";
    return true;
}

function run_sql(PDO $pdo, string $sql, bool $tolerant=false): void {
    $sql = preg_replace('!/\\*.*?\\*/!s','',$sql);
    $sql = preg_replace('/^--.*$/m','',$sql);
    foreach (array_filter(array_map('trim', explode(";\n", $sql))) as $stmt) {
        if ($stmt==='') continue;
        try {
            // Use query() so we can explicitly close the cursor; this prevents
            // PDO error 2014 ("unbuffered queries active") when a statement
            // (e.g. SELECT 1; or CALL proc) returns a result set.
            $st = $pdo->query($stmt);
            if ($st instanceof PDOStatement) {
                // drain any result rows, then free the cursor
                try { while ($st->fetch()) {} } catch (Throwable $e) {}
                try { do {} while ($st->nextRowset()); } catch (Throwable $e) {}
                $st->closeCursor();
            }
        } catch (Throwable $e) {
            if (!$tolerant) throw $e;
            $msg=$e->getMessage();
            // ignore redundant/MariaDB-syntax issues in tolerant (module) mode
            // (duplicate column/key, table exists, IF NOT EXISTS alter syntax)
            continue;
        }
    }
}

/* ---------- handle submit ---------- */
$errors=[]; $log=[];
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='install') {
    $in = [
        'db_host'=>trim($_POST['db_host']??'localhost'),
        'db_port'=>trim($_POST['db_port']??''),
        'db_socket'=>trim($_POST['db_socket']??''),
        'db_name'=>trim($_POST['db_name']??''), 'db_user'=>trim($_POST['db_user']??''),
        'db_pass'=>$_POST['db_pass']??'',
        'app_name'=>trim($_POST['app_name']??'Blackhawk CRM'),
        'app_url'=>rtrim(trim($_POST['app_url']??''),'/'),
        'tz'=>trim($_POST['tz']??'America/Chicago'),
        'admin_name'=>trim($_POST['admin_name']??''), 'admin_email'=>trim($_POST['admin_email']??''),
        'admin_pass'=>$_POST['admin_pass']??'',
    ];
    foreach (['db_name','db_user','app_url','admin_email','admin_pass'] as $req)
        if ($in[$req]==='') $errors[]="Missing: $req";
    if (strlen($in['admin_pass'])<8) $errors[]="Admin password must be at least 8 characters.";
    if (!$errors){
        $_SESSION['installing']=true;
        if (do_install($in,$log)){ unset($_SESSION['installing']); $step='done'; }
        else { $step='install'; }
    } else { $step='install'; }
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Blackhawk CRM Installer</title>
<style>
  *{box-sizing:border-box} body{font-family:system-ui,Segoe UI,Roboto,sans-serif;background:#0f172a;color:#1e293b;margin:0}
  .wrap{max-width:640px;margin:40px auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.4)}
  .hd{background:linear-gradient(135deg,#1e3a8a,#2563eb);color:#fff;padding:28px 32px}
  .hd h1{margin:0;font-size:24px} .hd p{margin:6px 0 0;opacity:.85;font-size:14px}
  .bd{padding:28px 32px}
  label{display:block;font-weight:600;font-size:13px;margin:14px 0 5px;color:#334155}
  input{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px}
  .row{display:flex;gap:12px} .row>div{flex:1}
  .btn{background:#2563eb;color:#fff;border:0;padding:12px 22px;border-radius:8px;font-weight:700;font-size:15px;cursor:pointer;margin-top:20px;width:100%}
  .chk{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:14px}
  .ok{color:#16a34a;font-weight:700} .bad{color:#dc2626;font-weight:700}
  .err{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px;border-radius:8px;margin:8px 0;font-size:14px}
  .log{background:#0f172a;color:#86efac;font-family:monospace;font-size:12px;padding:14px;border-radius:8px;white-space:pre-wrap;max-height:300px;overflow:auto}
  .muted{color:#64748b;font-size:13px}
</style></head><body><div class="wrap">
<div class="hd"><h1>Blackhawk CRM Installer</h1><p>Single-tenant edition — self-hosted setup</p></div>
<div class="bd">
<?php if ($step==='already'): ?>
  <div class="err">It looks like Blackhawk CRM is already installed (config.local.php exists). If you want to reinstall, delete <code>config.local.php</code> first.</div>
  <p><a href="../">Go to your Blackhawk CRM login →</a></p>

<?php elseif ($step==='welcome'): ?>
  <h2>Requirements check</h2>
  <?php $allok=true; foreach (requirements() as $r): if(!$r[1])$allok=false; ?>
    <div class="chk"><span><?=h($r[0])?></span><span class="<?=$r[1]?'ok':'bad'?>"><?=$r[1]?'✓ ':'✗ '?><?=h($r[2])?></span></div>
  <?php endforeach; ?>
  <?php if($allok): ?>
    <a class="btn" style="display:block;text-align:center;text-decoration:none" href="?step=install">Continue →</a>
  <?php else: ?>
    <div class="err" style="margin-top:16px">Please resolve the items marked ✗ (usually via your cPanel PHP-extension selector or file permissions), then reload.</div>
  <?php endif; ?>

<?php elseif ($step==='install'): ?>
  <h2>Configuration</h2>
  <?php foreach($errors as $e): ?><div class="err"><?=h($e)?></div><?php endforeach; ?>
  <?php if($log): ?><div class="log"><?=h(implode("\n",$log))?></div><?php endif; ?>
  <form method="post"><input type="hidden" name="action" value="install">
    <p class="muted">Create a MySQL database + user in cPanel first, then enter them here.</p>
    <div class="row"><div><label>DB host</label><input name="db_host" value="<?=h($_POST['db_host']??'localhost')?>"></div>
      <div><label>DB name</label><input name="db_name" value="<?=h($_POST['db_name']??'')?>" placeholder="cpuser_webpsa"></div></div>
    <p class="muted" style="margin:4px 0 0;font-size:12px;">If <code>localhost</code> fails with “No such file or directory”, the installer auto-retries <code>127.0.0.1</code>. For a non-standard MySQL socket/port, fill the optional fields below.</p>
    <div class="row"><div><label>DB port (optional)</label><input name="db_port" value="<?=h($_POST['db_port']??'')?>" placeholder="3306"></div>
      <div><label>DB socket (optional)</label><input name="db_socket" value="<?=h($_POST['db_socket']??'')?>" placeholder="/var/run/mysqld/mysqld.sock"></div></div>
    <div class="row"><div><label>DB user</label><input name="db_user" value="<?=h($_POST['db_user']??'')?>" placeholder="cpuser_psa"></div>
      <div><label>DB password</label><input name="db_pass" type="password"></div></div>
    <hr style="margin:20px 0;border:0;border-top:1px solid #e2e8f0">
    <div class="row"><div><label>Site name</label><input name="app_name" value="<?=h($_POST['app_name']??'Blackhawk CRM')?>"></div>
      <div><label>Timezone</label>
        <?php $__tzsel = $_POST['tz'] ?? 'America/Chicago'; ?>
        <select name="tz">
          <?php
            $__common = ['America/New_York','America/Chicago','America/Denver','America/Phoenix','America/Los_Angeles','America/Anchorage','Pacific/Honolulu','UTC'];
            echo '<optgroup label="Common (US)">';
            foreach ($__common as $__z) {
                $__s = ($__z === $__tzsel) ? ' selected' : '';
                echo '<option value="'.h($__z).'"'.$__s.'>'.h($__z).'</option>';
            }
            echo '</optgroup><optgroup label="All time zones">';
            foreach (DateTimeZone::listIdentifiers() as $__z) {
                $__s = ($__z === $__tzsel) ? ' selected' : '';
                echo '<option value="'.h($__z).'"'.$__s.'>'.h($__z).'</option>';
            }
            echo '</optgroup>';
          ?>
        </select>
      </div></div>
    <?php
    // Auto-detect the install URL (scheme + host + subfolder), so subfolder
    // installs prefill correctly and the user doesn't have to work out the path.
    $__scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || (($_SERVER['HTTP_X_FORWARDED_PROTO']??'')==='https') ? 'https' : 'http';
    $__host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $__base   = rtrim(str_replace('\\','/', dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/'))), '/'); // strip "/install"
    if ($__base==='/'||$__base==='.') $__base='';
    $__auto_url = $__scheme.'://'.$__host.$__base;
    ?>
    <label>Site URL</label><input name="app_url" value="<?=h($_POST['app_url'] ?? $__auto_url)?>" placeholder="https://crm.yourdomain.com">
    <p class="muted" style="margin-top:4px;font-size:12px;">Auto-detected. Include the subfolder if installed in one (e.g. <code><?=h($__auto_url)?></code>).</p>
    <hr style="margin:20px 0;border:0;border-top:1px solid #e2e8f0">
    <h3 style="margin:0 0 4px">Admin account</h3>
    <div class="row"><div><label>Your name</label><input name="admin_name" value="<?=h($_POST['admin_name']??'')?>"></div>
      <div><label>Email (login)</label><input name="admin_email" type="email" value="<?=h($_POST['admin_email']??'')?>"></div></div>
    <label>Admin password (min 8 chars)</label><input name="admin_pass" type="password">
    <button class="btn">Install Blackhawk CRM</button>
  </form>

<?php elseif ($step==='done'): ?>
  <div style="text-align:center;padding:6px 0 2px">
    <div style="width:74px;height:74px;border-radius:50%;background:#dcfce7;color:#16a34a;display:flex;align-items:center;justify-content:center;font-size:34px;margin:0 auto 14px">&#10003;</div>
    <h2 style="margin:0 0 6px">Installation complete!</h2>
    <p class="muted" style="margin:0 0 18px">Your CRM is installed and your admin account is ready.</p>
  </div>
  <?php if($log): ?><div class="log"><?=h(implode("\n",$log))?></div><?php endif; ?>

  <div style="display:flex;align-items:center;gap:10px;margin:22px 0 12px;color:#94a3b8;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase">
    <span style="flex:1;height:1px;background:#e2e8f0"></span>Recommended next steps<span style="flex:1;height:1px;background:#e2e8f0"></span>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
    <a href="../invoices/settings" target="_blank" rel="noopener" style="display:block;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;text-decoration:none;color:#1e293b;position:relative">
      <span style="position:absolute;top:10px;right:12px;color:#cbd5e1;font-size:12px">&#8599;</span>
      <span style="display:flex;width:38px;height:38px;border-radius:10px;background:#eef2ff;color:#4f46e5;align-items:center;justify-content:center;font-size:17px;margin-bottom:8px">&#128179;</span>
      <strong style="font-size:14px;display:block;margin-bottom:2px">Set up payments</strong>
      <span class="muted" style="font-size:12.5px;line-height:1.45;display:block">Connect PayPal &amp; Stripe so invoices, bookings, and the store can take money.</span>
    </a>
    <a href="../settings/smtp" target="_blank" rel="noopener" style="display:block;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;text-decoration:none;color:#1e293b;position:relative">
      <span style="position:absolute;top:10px;right:12px;color:#cbd5e1;font-size:12px">&#8599;</span>
      <span style="display:flex;width:38px;height:38px;border-radius:10px;background:#fef3c7;color:#b45309;align-items:center;justify-content:center;font-size:17px;margin-bottom:8px">&#9993;</span>
      <strong style="font-size:14px;display:block;margin-bottom:2px">Set up email</strong>
      <span class="muted" style="font-size:12.5px;line-height:1.45;display:block">Configure SMTP so ticket replies, invoices, and notifications actually send.</span>
    </a>
  </div>
  <p class="muted" style="font-size:12px;margin:10px 0 0;text-align:center">Both open in a new window and will ask you to log in first. Email is required before the CRM can send anything.</p>

  <div class="err" style="background:#fffbeb;border-color:#fde68a;color:#92400e;margin-top:16px">
    <strong>Important:</strong> delete the <code>/install</code> folder now for security.
  </div>
  <a class="btn" style="display:block;text-align:center;text-decoration:none" href="../">Go to your CRM login &rarr;</a>
<?php endif; ?>
</div></div></body></html>