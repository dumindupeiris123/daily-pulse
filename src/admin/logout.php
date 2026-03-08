<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
logout_user();
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
