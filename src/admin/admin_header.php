<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title ?? 'Admin') ?> — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-w: 240px;
            --ink:       #0d1117;
            --accent:    #c8102e;
            --sans:      'Segoe UI', system-ui, sans-serif;
        }
        body { font-family: var(--sans); background: #f1f3f5; }

        .admin-sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--ink);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .admin-sidebar .brand {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            text-decoration: none;
            display: block;
        }
        .admin-sidebar .brand span { color: var(--accent); }

        .admin-nav .nav-section {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            padding: .75rem 1.25rem .25rem;
        }
        .admin-nav .nav-link {
            color: rgba(255,255,255,.7);
            padding: .55rem 1.25rem;
            font-size: .875rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            border-radius: 0;
            transition: background .15s, color .15s;
        }
        .admin-nav .nav-link:hover,
        .admin-nav .nav-link.active {
            background: rgba(255,255,255,.07);
            color: #fff;
        }
        .admin-nav .nav-link.active { border-left: 3px solid var(--accent); }
        .admin-nav .nav-link i { font-size: 1rem; width: 1.25rem; }

        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-content { padding: 1.5rem; }

        .stat-card { background: #fff; border-radius: 6px; padding: 1.25rem; border: none; }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: .8rem; color: #6c757d; text-transform: uppercase; letter-spacing: .07em; }

        .admin-table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .08em; font-weight: 600; color: #6c757d; border-top: none; }
        .badge-published { background: #d1fae5; color: #065f46; }
        .badge-draft     { background: #fef3c7; color: #92400e; }
        .badge-archived  { background: #f3f4f6; color: #6b7280; }
        .badge-admin     { background: #fee2e2; color: #991b1b; }
        .badge-editor    { background: #dbeafe; color: #1e40af; }
        .badge-author    { background: #f3e8ff; color: #6b21a8; }
    </style>
</head>
<body>

<aside class="admin-sidebar">
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="brand">Daily<span>Pulse</span> <small style="font-size:.65rem;opacity:.5;font-weight:400;">Admin</small></a>
    <nav class="admin-nav flex-grow-1 pt-2">
        <div class="nav-section">Content</div>
        <a href="<?= SITE_URL ?>/admin/dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="<?= SITE_URL ?>/admin/articles.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'articles.php' || basename($_SERVER['PHP_SELF']) === 'article_edit.php' ? 'active' : '' ?>">
            <i class="bi bi-newspaper"></i> Articles
        </a>
        <a href="<?= SITE_URL ?>/admin/categories.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : '' ?>">
            <i class="bi bi-tag"></i> Categories
        </a>
        <a href="<?= SITE_URL ?>/admin/comments.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'comments.php' ? 'active' : '' ?>">
            <i class="bi bi-chat-dots"></i> Comments
            <?php
            $pending = (int)(get_db()->query('SELECT COUNT(*) FROM comments WHERE is_approved = 0')->fetchColumn());
            if ($pending > 0): ?>
            <span class="badge ms-auto" style="background:var(--accent);font-size:.65rem;"><?= $pending ?></span>
            <?php endif; ?>
        </a>
        <?php if (is_admin()): ?>
        <div class="nav-section">Management</div>
        <a href="<?= SITE_URL ?>/admin/users.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Users
        </a>
        <?php endif; ?>
        <div class="nav-section">Site</div>
        <a href="<?= SITE_URL ?>/index.php" class="nav-link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> View Site
        </a>
        <a href="<?= SITE_URL ?>/admin/logout.php" class="nav-link">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
    <div style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.08);">
        <?php $u = current_user(); ?>
        <div style="font-size:.8rem;color:rgba(255,255,255,.5);">Logged in as</div>
        <div style="font-size:.875rem;color:#fff;font-weight:600;"><?= h($u['username']) ?></div>
        <div style="font-size:.72rem;color:var(--accent);text-transform:uppercase;letter-spacing:.07em;"><?= h($u['role']) ?></div>
    </div>
</aside>

<div class="admin-main">
<div class="admin-topbar">
    <div style="font-weight:600;font-size:.95rem;"><?= h($page_title ?? 'Dashboard') ?></div>
    <a href="<?= SITE_URL ?>/admin/article_edit.php" class="btn btn-sm" style="background:var(--accent);color:#fff;font-size:.8rem;">
        <i class="bi bi-plus-lg me-1"></i>New Article
    </a>
</div>
<div class="admin-content">
<?php foreach (get_flashes() as $f): ?>
<div class="alert alert-<?= h($f['type']) ?> alert-dismissible fade show" role="alert">
    <?= h($f['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endforeach; ?>
