<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$slug    = trim($_GET['slug'] ?? '');
$article = $slug ? get_article_by_slug($slug) : null;

if (!$article || $article['status'] !== 'published') {
    header('HTTP/1.0 404 Not Found');
    $page_title = '404 — Article Not Found | ' . SITE_NAME;
    require __DIR__ . '/includes/header.php';
    echo '<div class="container py-5 text-center"><h2>Article not found</h2><a href="' . SITE_URL . '/index.php" class="btn btn-dark mt-3">Back to Home</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

increment_views($article['id']);

$comments  = get_approved_comments($article['id']);
$tags      = get_article_tags($article['id']);
$related   = get_related_articles($article['id'], $article['category_id'], 3);
$trending  = get_trending_articles(4);
$page_title = h($article['title']) . ' — ' . SITE_NAME;

$comment_error   = '';
$comment_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($name) || empty($email) || empty($content)) {
        $comment_error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $comment_error = 'Please enter a valid email address.';
    } elseif (strlen($content) < 10) {
        $comment_error = 'Comment must be at least 10 characters.';
    } else {
        submit_comment($article['id'], $name, $email, $content);
        $comment_success = true;
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="container">
<div class="row">
    <div class="col-lg-8">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= SITE_URL ?>/src/index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= SITE_URL ?>/src/category.php?slug=<?= h($article['category_slug']) ?>" class="text-decoration-none">
                        <?= h($article['category_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?= h(substr($article['title'], 0, 40)) ?>…</li>
            </ol>
        </nav>

        <a href="<?= SITE_URL ?>/src/category.php?slug=<?= h($article['category_slug']) ?>" class="category-badge d-inline-block mb-3"
           style="background:var(--accent);color:#fff;font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;padding:.25rem .7rem;text-decoration:none;">
            <?= h($article['category_name']) ?>
        </a>

        <h1 style="font-family:var(--serif);font-weight:900;font-size:2.2rem;line-height:1.25;" class="mb-3">
            <?= h($article['title']) ?>
        </h1>

        <div class="d-flex flex-wrap gap-3 mb-4" style="font-size:.85rem;color:var(--ink-soft);">
            <span><i class="bi bi-person-circle me-1"></i><?= h($article['author_name']) ?></span>
            <span><i class="bi bi-calendar3 me-1"></i><?= format_date($article['published_at'] ?? $article['created_at'], 'F j, Y') ?></span>
            <span><i class="bi bi-clock me-1"></i><?= time_ago($article['published_at'] ?? $article['created_at']) ?></span>
            <span><i class="bi bi-eye me-1"></i><?= number_format($article['views']) ?> views</span>
            <span><i class="bi bi-chat me-1"></i><?= count($comments) ?> comments</span>
        </div>

        <?php if ($article['image_url']): ?>
        <img src="<?= h($article['image_url']) ?>" class="article-hero-img rounded mb-4" alt="<?= h($article['title']) ?>">
        <?php else: ?>
        <img src="https://picsum.photos/seed/<?= $article['id'] ?>/900/400" class="article-hero-img rounded mb-4" alt="">
        <?php endif; ?>

        <?php if ($article['excerpt']): ?>
        <p class="lead mb-4" style="font-family:var(--serif);font-style:italic;color:var(--ink-soft);border-left:3px solid var(--accent);padding-left:1rem;">
            <?= h($article['excerpt']) ?>
        </p>
        <?php endif; ?>

        <div class="article-body mb-4">
            <?= nl2br(h($article['content'])) ?>
        </div>

        <?php if ($tags): ?>
        <div class="mb-4">
            <strong style="font-size:.85rem;text-transform:uppercase;letter-spacing:.07em;">Tags:</strong>
            <?php foreach ($tags as $tag): ?>
            <span class="tag-badge"><?= h($tag['name']) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <hr class="my-4">

        <div class="mb-5">
            <h4 style="font-family:var(--serif);" class="mb-4">
                <?= count($comments) ?> Comment<?= count($comments) !== 1 ? 's' : '' ?>
            </h4>

            <?php foreach ($comments as $c): ?>
            <div class="d-flex mb-3 pb-3 border-bottom">
                <div class="flex-shrink-0 me-3">
                    <div style="width:40px;height:40px;background:var(--ink);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.9rem;">
                        <?= strtoupper(substr(h($c['name']), 0, 1)) ?>
                    </div>
                </div>
                <div>
                    <strong><?= h($c['name']) ?></strong>
                    <span class="text-muted ms-2" style="font-size:.8rem;"><?= time_ago($c['created_at']) ?></span>
                    <p class="mb-0 mt-1" style="font-size:.95rem;"><?= h($c['content']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if ($comment_success): ?>
            <div class="alert alert-success">Your comment has been submitted and is pending approval. Thank you!</div>
            <?php else: ?>
            <div class="card border-0 bg-white p-4 mt-4" style="border:1px solid var(--border) !important;">
                <h5 style="font-family:var(--serif);" class="mb-3">Leave a Comment</h5>
                <?php if ($comment_error): ?>
                <div class="alert alert-danger"><?= h($comment_error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Your Name *" value="<?= h($_POST['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Email Address *" value="<?= h($_POST['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <textarea name="content" class="form-control" rows="4" placeholder="Your comment… *" required><?= h($_POST['content'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="submit_comment" class="btn btn-dark px-4">
                                <i class="bi bi-send me-1"></i>Post Comment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <div class="col-lg-4">
        <?php if ($related): ?>
        <div class="sidebar-section mb-4">
            <h6>Related Stories</h6>
            <?php foreach ($related as $r): ?>
            <div class="d-flex mb-3 pb-3 border-bottom">
                <img src="<?= h($r['image_url'] ?: 'https://picsum.photos/seed/' . $r['id'] . '/200/150') ?>"
                     style="width:80px;height:60px;object-fit:cover;flex-shrink:0;" class="me-3 rounded" alt="">
                <div>
                    <a href="<?= SITE_URL ?>/src/article.php?slug=<?= h($r['slug']) ?>" class="text-decoration-none text-dark" style="font-family:var(--serif);font-size:.9rem;font-weight:700;line-height:1.3;">
                        <?= h($r['title']) ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="sidebar-section">
            <h6>Trending</h6>
            <?php foreach ($trending as $i => $t): ?>
            <div class="trending-item d-flex align-items-start">
                <span class="num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                <div>
                    <a href="<?= SITE_URL ?>/src/article.php?slug=<?= h($t['slug']) ?>"><?= h($t['title']) ?></a>
                    <div style="font-size:.72rem;color:var(--ink-soft);margin-top:.2rem;"><?= h($t['category_name']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div><!-- /container -->

<?php require __DIR__ . '/includes/footer.php'; ?>
