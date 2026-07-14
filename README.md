# Blackhawk MSP Platform
By Ryan C. Smith
https://perfect-crm.com


Pure PHP modular MSP management platform. No frameworks, no WordPress.

## Requirements

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- Apache with `mod_rewrite` (or Nginx with equivalent config)
- OpenSSL, PDO, cURL, ZipArchive extensions

## Installation

1. **Upload** the contents of this folder to your web server (the `public/` folder should be your document root, or point a subdirectory to it)

2. **Navigate** to `https://yourdomain.com/install.php`

3. **Fill in** database credentials and admin account details

4. **Click Install** — the installer creates the database, writes config, and sets up storage

5. **Delete `install.php`** immediately after installation

6. **Log in** at `https://yourdomain.com/login`

## Installing Modules

1. Go to **Settings → Modules**
2. Click **Upload & Install**, select a `.zip` module file
3. The module installs instantly — its tables are created and it appears in the sidebar

## Nginx Config (if not using Apache)

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Directory Structure

```
blackhawk/
├── public/          ← Web root (point your domain here)
│   ├── index.php    ← Front controller
│   ├── .htaccess    ← Apache rewrite rules
│   └── assets/      ← CSS, JS, images
├── core/            ← Framework classes (DB, Auth, Router, View...)
├── controllers/     ← Core controllers (Auth, Dashboard, Settings)
├── views/           ← Core view templates
├── modules/         ← Installed modules (clients, tickets, invoices...)
├── migrations/      ← Core SQL schema
├── config/
│   ├── config.php        ← Main config (edit defaults here)
│   └── config.local.php  ← Local overrides (written by installer, gitignored)
├── storage/
│   ├── cache/       ← File-based cache
│   ├── uploads/     ← User file uploads
│   └── logs/        ← Application logs
└── install.php      ← One-time installer (delete after use)
```

## Default Login

After installation, log in with the admin credentials you set during install.

## Security Checklist

- [ ] Delete `install.php` after installation
- [ ] Set a strong `BH_ENCRYPTION_KEY` (done automatically by installer)
- [ ] Ensure `storage/` and `config/config.local.php` are not web-accessible
- [ ] Enable HTTPS
- [ ] Change the default admin password if you used the default
