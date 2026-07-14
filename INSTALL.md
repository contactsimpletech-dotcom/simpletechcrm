# WebPSA — cPanel / Self-Hosted Edition — Install Guide
By Ryan C. Smith
https://perfect-crm.com
ryan@blackhawkmsp.com

Single-tenant build. No SaaS, no billing, no phone-home, no licensing.
Designed to install directly into `public_html` — no document-root changes needed.

## Requirements
- cPanel hosting, PHP **8.1+** (set in "Select PHP Version")
- Extensions: pdo_mysql (or nd_pdo_mysql), mbstring, openssl, json, curl, gd, zip
- One MySQL database + user (cPanel → MySQL Databases; grant the user ALL on the db)

## Install
1. **Upload & extract** the zip so its contents land **directly in `public_html/`**.
   You should see `index.php`, `config/`, `modules/`, `install/`, `assets/`, etc. right in `public_html`.
   (There is no `public/` subfolder — this build is flattened for cPanel.)
2. **Create a MySQL database + user** in cPanel; note the name, user, password.
3. **Run the installer**: visit `https://yourdomain.com/install/` (if that 403s on directory
   index, use `https://yourdomain.com/install/index.php`).
   Enter DB credentials, your site name/URL, and admin login. Click **Install**.
   The installer builds the schema, activates all modules, creates your admin user, and
   writes `config/config.local.php`.
4. **Delete the `install/` folder** when finished (important for security).
5. **Log in** at `https://yourdomain.com/` with the admin email/password you set.

## How it stays secure in public_html
- The root `.htaccess` routes all requests through `index.php` and returns **403 for**
  `config/`, `core/`, `modules/`, `vendor/`, `views/`, `storage/`, `cron/`.
- Each of those folders also has its own `.htaccess` deny (defense in depth).
- `config/config.local.php` (your DB password + encryption key) is non-web-served.
- Still: delete `install/` after setup.

## Email
Configure outbound SMTP in Settings after install (or in `config/config.local.php`).
Email-to-ticket uses IMAP polling (Tickets → Settings) — no server mail pipe needed.

## Cron (optional)
cPanel → Cron Jobs, every 5 min: `php /home/USER/public_html/cron/run.php`

## Updating
Replace application files but **keep** `config/config.local.php` and `storage/`.
Your data lives in MySQL + `storage/`, untouched by a file replace.

## Troubleshooting
- **Redirects to http://localhost** → `config/config.local.php` missing or BH_APP_URL wrong.
- **Login page unstyled** → assets blocked; confirm the root `.htaccess` uploaded (it's a dotfile).
- **Install shows few tables** → check `install/schema-errors.log` for the DB error.
