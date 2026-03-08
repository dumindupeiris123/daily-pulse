<?php
// Database configuration
define('DB_HOST',     'localhost');
define('DB_SOCKET',   '/home/sakithb/Projects/dumindu-ayya/.mysql/mysql.sock');
define('DB_NAME',     'news_db');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_CHARSET',  'utf8mb4');

// Application configuration
define('SITE_NAME',   'The Daily Pulse');
define('SITE_URL',    'http://localhost:8181');
define('ADMIN_EMAIL', 'admin@dailypulse.com');

// Session
define('SESSION_LIFETIME', 3600); // 1 hour

// Pagination
define('ARTICLES_PER_PAGE', 6);
define('ADMIN_PER_PAGE',    10);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Colombo');
