<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = SITE_NAME . ' — Breaking News & In-Depth Analysis';

$page        = max(1, (int) ($_GET['page'] ?? 1));
$offset      = ($page - 1) * ARTICLES_PER_PAGE;
$total       = count_published_articles();
$total_pages = (int) ceil($total / ARTICLES_PER_PAGE);

$articles    = get_published_articles(ARTICLES_PER_PAGE, $offset);
$hero        = array_shift($articles);
$trending    = get_trending_articles(5);
$categories  = get_all_categories();

require __DIR__ . '/includes/header.php';
?>

<div class="container">

<?php if ($page === 1 && $hero): ?>
<div class="row mb-5">
    <div class="col-lg-8">
        <div class="hero-article">
            <a href="<?= SITE_URL ?>/article.php?slug=<?= h($hero['slug']) ?>">
                <img src="<?= h($hero['image_url'] ?: 'https://picsum.photos/seed/' . $hero['id'] . '/900/480') ?>" alt="<?= h($hero['title']) ?>">
            </a>
            <div class="hero-overlay">
                <a href="<?= SITE_URL ?>/category.php?slug=<?= h($hero['category_slug']) ?>" class="category-badge"><?= h($hero['category_name']) ?></a>
                <h2>
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= h($hero['slug']) ?>" class="text-white text-decoration-none">
                        <?= h($hero['title']) ?>
                    </a>
                </h2>
                <div class="meta">
                    <i class="bi bi-person me-1"></i><?= h($hero['author_name']) ?>
                    &nbsp;&bull;&nbsp;
                    <i class="bi bi-clock me-1"></i><?= time_ago($hero['published_at'] ?? $hero['created_at']) ?>
                    &nbsp;&bull;&nbsp;
                    <i class="bi bi-eye me-1"></i><?= number_format($hero['views']) ?> views
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="sidebar-section">
            <h6>Trending Now</h6>
            <?php foreach ($trending as $i => $t): ?>
            <div class="trending-item d-flex align-items-start">
                <span class="num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                <div>
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= h($t['slug']) ?>">
                        <?= h($t['title']) ?>
                    </a>
                    <div class="meta" style="font-size:.72rem;color:var(--ink-soft);margin-top:.2rem;">
                        <?= h($t['category_name']) ?> &bull; <?= number_format($t['views']) ?> views
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="section-label">Latest Stories</div>
<div class="row g-4 mb-4">
    <?php foreach ($articles as $article): ?>
    <div class="col-md-6 col-lg-4">
        <div class="article-card card h-100">
            <a href="<?= SITE_URL ?>/article.php?slug=<?= h($article['slug']) ?>">
                <img src="<?= h($article['image_url'] ?: 'https://picsum.photos/seed/' . $article['id'] . '/600/300') ?>"
                     class="card-img-top" alt="<?= h($article['title']) ?>">
            </a>
            <div class="card-body">
                <a href="<?= SITE_URL ?>/category.php?slug=<?= h($article['category_slug']) ?>" class="category-badge">
                    <?= h($article['category_name']) ?>
                </a>
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
<nav class="d-flex justify-content-center mb-5">
    <ul class="pagination">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="bi bi-chevron-left"></i></a>
        </li>
        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
        <li class="page-item <?= $p === $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
        </li>
        <?php endfor; ?>
        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>"><i class="bi bi-chevron-right"></i></a>
        </li>
    </ul>
</nav>
<?php endif; ?>

</div><!-- /container -->

<?php require __DIR__ . '/includes/footer.php'; ?>
