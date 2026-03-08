<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$id      = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$article = $id ? get_article_by_id($id) : null;
$is_edit = $article !== null;
$errors  = [];

$categories = get_all_categories();
$user       = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token. Please refresh and try again.';
    } else {
        $title      = trim($_POST['title'] ?? '');
        $excerpt    = trim($_POST['excerpt'] ?? '');
        $content    = trim($_POST['content'] ?? '');
        $image_url  = trim($_POST['image_url'] ?? '');
        $category_id = (int) ($_POST['category_id'] ?? 0);
        $status     = $_POST['status'] ?? 'draft';
        $slug       = slugify($title);

        if (empty($title))       $errors[] = 'Title is required.';
        if (empty($content))     $errors[] = 'Content is required.';
        if ($category_id <= 0)   $errors[] = 'Please select a category.';
        if (!in_array($status, ['draft', 'published', 'archived'])) $errors[] = 'Invalid status.';

        if (empty($errors)) {
            $data = compact('title', 'slug', 'excerpt', 'content', 'image_url', 'category_id', 'status');
            $data['author_id'] = $user['id'];

            if ($is_edit) {
                admin_update_article($id, $data);
                flash('success', 'Article updated successfully.');
                header('Location: ' . SITE_URL . '/admin/articles.php');
            } else {
                $new_id = admin_create_article($data);
                flash('success', 'Article created successfully.');
                header('Location: ' . SITE_URL . '/admin/article_edit.php?id=' . $new_id);
            }
            exit;
        }
    }
}

$page_title = $is_edit ? 'Edit Article' : 'New Article';
$values = $is_edit && empty($_POST) ? $article : $_POST;

require __DIR__ . '/admin_header.php';
?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" id="articleForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" style="font-size:1.1rem;font-weight:600;"
                               value="<?= h($values['title'] ?? '') ?>" placeholder="Article headline…" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Excerpt <span class="text-muted">(optional summary)</span></label>
                        <textarea name="excerpt" class="form-control" rows="2" placeholder="Short summary for article cards and SEO…"><?= h($values['excerpt'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="16" placeholder="Write the full article content here…" required><?= h($values['content'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Featured Image URL <span class="text-muted">(optional)</span></label>
                        <input type="url" name="image_url" class="form-control" value="<?= h($values['image_url'] ?? '') ?>"
                               placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn px-4" style="background:var(--accent);color:#fff;">
                            <i class="bi bi-check-lg me-1"></i><?= $is_edit ? 'Update Article' : 'Create Article' ?>
                        </button>
                        <a href="<?= SITE_URL ?>/src/admin/articles.php" class="btn btn-outline-secondary">Cancel</a>
                        <?php if ($is_edit && $article['status'] === 'published'): ?>
                        <a href="<?= SITE_URL ?>/src/article.php?slug=<?= h($article['slug']) ?>" target="_blank" class="btn btn-outline-success ms-auto">
                            <i class="bi bi-box-arrow-up-right me-1"></i>View Live
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-2">
                <strong style="font-size:.85rem;">Publish Settings</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Status</label>
                    <select name="status" form="articleForm" class="form-select">
                        <option value="draft"     <?= ($values['status'] ?? 'draft') === 'draft'     ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($values['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived"  <?= ($values['status'] ?? '') === 'archived'  ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Category <span class="text-danger">*</span></label>
                    <select name="category_id" form="articleForm" class="form-select" required>
                        <option value="">Select category…</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ((int)($values['category_id'] ?? 0)) === (int)$cat['id'] ? 'selected' : '' ?>>
                            <?= h($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($is_edit): ?>
                <div style="font-size:.8rem;color:#6c757d;">
                    <div><strong>Created:</strong> <?= format_date($article['created_at'], 'M j, Y H:i') ?></div>
                    <div><strong>Updated:</strong> <?= format_date($article['updated_at'], 'M j, Y H:i') ?></div>
                    <?php if ($article['published_at']): ?>
                    <div><strong>Published:</strong> <?= format_date($article['published_at'], 'M j, Y H:i') ?></div>
                    <?php endif; ?>
                    <div class="mt-1"><strong>Views:</strong> <?= number_format($article['views']) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($is_edit): ?>
        <div class="card border-0 shadow-sm border border-danger-subtle">
            <div class="card-body">
                <h6 class="text-danger mb-2" style="font-size:.85rem;">Danger Zone</h6>
                <form method="POST" action="<?= SITE_URL ?>/src/admin/articles.php" onsubmit="return confirm('Permanently delete this article?');">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="delete_id" value="<?= $article['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i>Delete Article
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
