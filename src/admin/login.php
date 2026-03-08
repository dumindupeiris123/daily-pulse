<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $error    = '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter your username and password.';
    } elseif (!login_user($username, $password)) {
        $error = 'Invalid credentials. Please try again.';
    } else {
        $redirect = $_SESSION['redirect_after_login'] ?? (SITE_URL . '/admin/dashboard.php');
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    }
}

if (is_logged_in()) {
    header('Location: ' . SITE_URL . '/admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #0d1117; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: #fff; width: 100%; max-width: 400px; padding: 2.5rem; border-radius: 4px; }
        .login-brand { font-size: 2rem; font-weight: 900; color: #0d1117; font-family: Georgia, serif; }
        .login-brand span { color: #c8102e; }
        .btn-login { background: #c8102e; color: #fff; border: none; font-weight: 600; }
        .btn-login:hover { background: #9b0c22; color: #fff; }
        .form-control:focus { border-color: #c8102e; box-shadow: 0 0 0 .2rem rgba(200,16,46,.2); }
    </style>
</head>
<body>
<div class="login-box shadow-lg">
    <div class="text-center mb-4">
        <div class="login-brand">Daily<span>Pulse</span></div>
        <div style="font-size:.8rem;color:#6c757d;text-transform:uppercase;letter-spacing:.1em;margin-top:.25rem;">Admin Panel</div>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2" style="font-size:.875rem;">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= h($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label" style="font-size:.85rem;font-weight:600;">Username or Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" value="<?= h($_POST['username'] ?? '') ?>" placeholder="admin" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label" style="font-size:.85rem;font-weight:600;">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        <button type="submit" class="btn btn-login w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="<?= SITE_URL ?>/src/index.php" style="font-size:.8rem;color:#6c757d;">← Back to site</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
