<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$slug     = trim($_GET['slug'] ?? '');
$category = $slug ? get_category_by_slug($slug) : null;

if (!$category) {
    header('HTTP/1.0 404 Not Found');
    $page_title = '404 — Category Not Found | ' . SITE_NAME;
    require __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center"><h2>Category not found</h2><a href="' . SITE_URL . '/index.php" class="btn btn-dark mt-3">Back to Home</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$page        = max(1, (int) ($_GET['page'] ?? 1));
$offset      = ($page - 1) * ARTICLES_PER_PAGE;
$total       = count_published_articles($category['id']);
$total_pages = (int) ceil($total / ARTICLES_PER_PAGE);
$articles    = get_published_articles(ARTICLES_PER_PAGE, $offset, $category['id']);
$trending    = get_trending_articles(4);
$categories  = get_all_categories();
$page_title  = h($category['name']) . ' — ' . SITE_NAME;

require __DIR__ . '/includes/header.php';
?>

<div class="container">

<div class="d-flex justify-content-between align-items-end border-bottom border-2 border-dark pb-2 mb-4" style="border-color: var(--accent) !important;">
    <div>
        <h1 class="h2 mb-0" style="font-family:var(--serif);"><?= h($category['name']) ?></h1>
        <?php if ($category['description']): ?>
        <p class="text-muted mb-0" style="font-size:.9rem;"><?= h($category['description']) ?></p>
        <?php endif; ?>
    </div>
    <span class="text-muted" style="font-size:.85rem;"><?= $total ?> article<?= $total !== 1 ? 's' : '' ?></span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <?php if (empty($articles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-newspaper" style="font-size:3rem;color:var(--border);"></i>
                <p class="mt-3 text-muted">No articles in this category yet.</p>
            </div>
        <?php else: ?>
        <div class="row g-4 mb-4">
            <?php foreach ($articles as $article): ?>
            <div class="col-md-6">
                <div class="article-card card h-100">
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= h($article['slug']) ?>">
                        <img src="<?= h($article['image_url'] ?: 'https://picsum.photos/seed/' . $article['id'] . '/600/300') ?>"
                             class="card-img-top" alt="<?= h($article['title']) ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?= SITE_URL ?>/article.php?slug=<?= h($article['slug']) ?>" class="text-decoration-none text-dark">
                                <?= h($article['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted" style="font-size:.9rem;"><?= h(excerpt($article['excerpt'] ?: $article['content'], 18)) ?></p>
                        <div class="meta d-flex gap-3">
                            <span><i class="bi bi-person me-1"></i><?= h($article['author_name']) ?></span>
                            <span><i class="bi bi-clock me-1"></i><?= time_ago($article['published_at'] ?? $article['created_at']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <nav class="d-flex justify-content-center mb-4">
            <ul class="pagination">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?slug=<?= h($slug) ?>&page=<?= $page - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?slug=<?= h($slug) ?>&page=<?= $p ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?slug=<?= h($slug) ?>&page=<?= $page + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="sidebar-section mb-4">
            <h6>Trending</h6>
            <?php foreach ($trending as $i => $t): ?>
            <div class="trending-item d-flex align-items-start">
                <span class="num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                <div>
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= h($t['slug']) ?>"><?= h($t['title']) ?></a>
                    <div style="font-size:.72rem;color:var(--ink-soft);margin-top:.2rem;"><?= h($t['category_name']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="sidebar-section">
            <h6>All Categories</h6>
            <ul class="list-unstyled">
                <?php foreach ($categories as $cat): ?>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <a href="<?= SITE_URL ?>/category.php?slug=<?= h($cat['slug']) ?>" class="text-decoration-none text-dark <?= $cat['id'] === $category['id'] ? 'fw-bold' : '' ?>">
                        <?= h($cat['name']) ?>
                    </a>
                    <span class="badge rounded-pill" style="background:var(--ink);font-size:.7rem;"><?= $cat['article_count'] ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

</div><!-- /container -->

<?php require __DIR__ . '/includes/footer.php'; ?>
