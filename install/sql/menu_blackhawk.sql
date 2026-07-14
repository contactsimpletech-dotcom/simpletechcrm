-- Curate the nav to mirror Blackhawk MSP's clean menu.
-- Creates bh_nav_items if missing (MariaDB-safe), then sets the exact menu:
-- visible items in order, everything else hidden, hub links injected.

CREATE TABLE IF NOT EXISTS `bh_nav_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nav_key` varchar(100) NOT NULL DEFAULT '',
  `label` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `parent_key` varchar(100) NOT NULL DEFAULT '',
  `sort_order` int NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `is_custom` tinyint(1) NOT NULL DEFAULT 0,
  `is_folder` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nav_key` (`nav_key`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_key` (`parent_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Start clean
DELETE FROM bh_nav_items;

-- ── VISIBLE menu, in Blackhawk order ──────────────────────────────────────────
INSERT INTO bh_nav_items (nav_key,label,url,icon,sort_order,hidden,is_custom) VALUES
('dashboard',    'Dashboard',      '/dashboard',         'fa-solid fa-gauge',           10, 0, 0),
('clients',      'Customers',      '/clients',           'fa-solid fa-building',        20, 0, 0),
('tickets',      'Tickets',        '/tickets',           'fa-solid fa-ticket',          30, 0, 0),
('invoices',     'Invoices',       '/invoices',          'fa-solid fa-file-invoice',    40, 0, 0),
('tasks',        'Tasks',          '/tasks',             'fa-solid fa-list-check',      50, 0, 0),
('glue',         'Glue Center',    '/glue',              'fa-solid fa-diagram-project', 60, 0, 0),
('expenses',     'Expenses',       '/expenses',          'fa-solid fa-receipt',         70, 0, 0),
('projects',     'Projects',       '/projects',          'fa-solid fa-diagram-project', 80, 0, 0),
('timesheets',   'Timesheets',     '/timesheets',        'fa-solid fa-clock',           90, 0, 0),
('assets',       'Assets',         '/assets',            'fa-solid fa-server',         100, 0, 0),
('kb',           'Knowledge Base', '/kb',                'fa-solid fa-book',           110, 0, 0),
('sales-center', 'Sales',          '/sales-center',      'fa-solid fa-bullhorn',       120, 0, 0),
('employees',    'Employees',      '/employees',         'fa-solid fa-users',          130, 0, 0),
('finance-center','Finances',      '/finance-center',    'fa-solid fa-chart-pie',      140, 0, 0),
('reports',      'Reports',        '/reports',           'fa-solid fa-chart-line',     150, 0, 0),
('misc',         'Misc',           '/misc',              'fa-solid fa-ellipsis',       170, 0, 0),
('billing',      'Billing',        '/invoices/settings', 'fa-solid fa-credit-card',    190, 0, 1),
('marketplace',  'Plugins',        '/marketplace',       'fa-solid fa-store',          200, 0, 0),
('help',         'Help',           '/help',              'fa-solid fa-circle-question',210, 0, 0);

-- ── HIDDEN: everything else that registers a nav item ─────────────────────────
INSERT INTO bh_nav_items (nav_key,sort_order,hidden) VALUES
('portal',1,1),('tacticalrmm',3,1),('bookkeeping',4,1),
('docs',6,1),('ai-helper',7,1),('importer',8,1),('invoice-importer',9,1),
('ticket-importer',10,1),('assessments-importer',11,1),('assessments-inbox',12,1),
('tax',13,1),('msp360',14,1),('simplehelp',15,1),('meraki',16,1),('zoom',17,1),
('webroot',18,1),('tuesday',19,1),('books',20,1),('mileage',21,1),('payroll',22,1),
('newsletter',23,1),('dropsuite',24,1),('privacy',26,1),
('site-help',27,1),('income-tracker',28,1),('chart-of-accounts',29,1),('audit',30,1),
('contracts',31,1),('assessments',32,1),('budget',33,1),('inventory',34,1),
('leads',35,1),('proposals',36,1),('vendors',37,1),('duo',38,1),('huntress',39,1),
('downloads',40,1),('screensaver',41,1),('my-account',42,1),('tax-ca',43,1),
('cron',44,1),('marketplace',45,1),('agents',46,1),('sentinelone',47,1),('orders',48,1),('passwords',49,1)
ON DUPLICATE KEY UPDATE hidden=1;
