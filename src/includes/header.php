<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title ?? SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --ink:       #0d1117;
            --ink-soft:  #4a5568;
            --accent:    #c8102e;
            --accent-dk: #9b0c22;
            --border:    #e2e8f0;
            --bg-warm:   #fafaf8;
            --serif:     'Playfair Display', Georgia, serif;
            --sans:      'Source Sans 3', system-ui, sans-serif;
        }
        body { font-family: var(--sans); color: var(--ink); background: var(--bg-warm); }
        h1,h2,h3,h4,h5 { font-family: var(--serif); }

        /* Top bar */
        .top-bar { background: var(--ink); color: #fff; font-size: .78rem; letter-spacing:.04em; }
        .top-bar a { color: rgba(255,255,255,.7); text-decoration:none; }
        .top-bar a:hover { color: #fff; }

        /* Masthead */
        .masthead { border-bottom: 3px solid var(--accent); padding: 1.4rem 0 1rem; }
        .masthead .brand { font-family: var(--serif); font-weight:900; font-size:2.6rem; color: var(--ink); text-decoration:none; line-height:1; }
        .masthead .brand span { color: var(--accent); }

        /* Navbar */
        .site-nav { background: var(--ink); }
        .site-nav .navbar-nav .nav-link { color: rgba(255,255,255,.85) !important; font-size:.875rem; letter-spacing:.06em; text-transform:uppercase; font-weight:600; padding: .6rem 1.1rem !important; }
        .site-nav .navbar-nav .nav-link:hover,
        .site-nav .navbar-nav .nav-link.active { color:#fff !important; background: var(--accent); }
        .site-nav .navbar-toggler { border-color: rgba(255,255,255,.3); }
        .site-nav .navbar-toggler-icon { filter: invert(1); }

        /* Cards */
        .article-card { border:none; border-radius:0; background:#fff; transition: box-shadow .2s; }
        .article-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.1); }
        .article-card .card-img-top { height:200px; object-fit:cover; }
        .article-card .category-badge { background: var(--accent); color:#fff; font-size:.7rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; padding:.2rem .6rem; text-decoration:none; display:inline-block; margin-bottom:.5rem; }
        .article-card .card-title { font-family: var(--serif); font-size:1.15rem; font-weight:700; line-height:1.3; }
        .article-card .meta { font-size:.78rem; color: var(--ink-soft); }

        /* Hero article */
        .hero-article { position:relative; overflow:hidden; }
        .hero-article img { width:100%; height:480px; object-fit:cover; }
        .hero-overlay { position:absolute; bottom:0; left:0; right:0; padding:2rem; background: linear-gradient(transparent, rgba(0,0,0,.82)); color:#fff; }
        .hero-overlay .category-badge { background: var(--accent); color:#fff; font-size:.72rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; padding:.2rem .6rem; text-decoration:none; display:inline-block; margin-bottom:.6rem; }
        .hero-overlay h2 { font-family: var(--serif); font-size:2rem; font-weight:900; line-height:1.2; margin-bottom:.4rem; }
        .hero-overlay .meta { font-size:.8rem; color: rgba(255,255,255,.8); }

        /* Sidebar */
        .sidebar-section h6 { font-family: var(--serif); font-size:1rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; border-bottom:2px solid var(--accent); padding-bottom:.4rem; margin-bottom:1rem; }
        .trending-item { border-bottom:1px solid var(--border); padding:.75rem 0; }
        .trending-item .num { font-size:2rem; font-weight:900; color: var(--border); line-height:1; margin-right:.75rem; font-family: var(--serif); }
        .trending-item a { font-family: var(--serif); font-weight:700; font-size:.95rem; color: var(--ink); text-decoration:none; }
        .trending-item a:hover { color: var(--accent); }

        /* Section divider */
        .section-label { font-family: var(--serif); font-size:1.25rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; border-left:4px solid var(--accent); padding-left:.75rem; margin-bottom:1.5rem; }

        /* Pagination */
        .pagination .page-link { color: var(--ink); border-color: var(--border); }
        .pagination .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color:#fff; }

        /* Footer */
        footer { background: var(--ink); color: rgba(255,255,255,.7); font-size:.875rem; }
        footer a { color: rgba(255,255,255,.6); text-decoration:none; }
        footer a:hover { color:#fff; }
        footer .footer-brand { font-family: var(--serif); font-size:1.6rem; font-weight:900; color:#fff; }
        footer .footer-brand span { color: var(--accent); }

        /* Tags */
        .tag-badge { background: var(--bg-warm); border:1px solid var(--border); color: var(--ink-soft); font-size:.75rem; padding:.25rem .65rem; text-decoration:none; display:inline-block; margin:.15rem; transition: background .15s; }
        .tag-badge:hover { background: var(--accent); color:#fff; border-color: var(--accent); }

        /* Article page */
        .article-body { font-size:1.1rem; line-height:1.85; }
        .article-body p { margin-bottom:1.4rem; }
        .article-hero-img { width:100%; max-height:480px; object-fit:cover; }
    </style>
</head>
<body>

<div class="top-bar py-1">
    <div class="container d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar3 me-1"></i><?= date('l, F j, Y') ?></span>
        <div>
            <?php if (is_logged_in()): ?>
                <a href="<?= SITE_URL ?>/src/admin/dashboard.php"><i class="bi bi-shield-lock me-1"></i>Admin</a>
                <span class="mx-2">|</span>
                <a href="<?= SITE_URL ?>/src/admin/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/src/admin/login.php"><i class="bi bi-person me-1"></i>Admin Login</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<header class="masthead">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="<?= SITE_URL ?>/src/index.php" class="brand">The Daily<span>Pulse</span></a>
        <div class="d-none d-md-block text-end">
            <div style="font-size:.75rem;color:var(--ink-soft);text-transform:uppercase;letter-spacing:.1em;">Breaking news &bull; In-depth analysis</div>
        </div>
    </div>
</header>

<nav class="site-nav navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/src/index.php">Home</a></li>
                <?php foreach (get_all_categories() as $cat): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= SITE_URL ?>/src/category.php?slug=<?= h($cat['slug']) ?>">
                        <?= h($cat['name']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/src/about.php">About</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php foreach (get_flashes() as $f): ?>
        <div class="alert alert-<?= h($f['type']) ?> alert-dismissible fade show" role="alert">
            <?= h($f['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
</div>
