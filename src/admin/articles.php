<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$page   = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * ADMIN_PER_PAGE;
$total  = admin_count_articles($search, $status);
$pages  = (int) ceil($total / ADMIN_PER_PAGE);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('danger', 'Invalid security token.');
    } else {
        $del_id = (int) $_POST['delete_id'];
        if (admin_delete_article($del_id)) {
            flash('success', 'Article deleted successfully.');
        } else {
            flash('danger', 'Could not delete article.');
        }
    }
    header('Location: ' . SITE_URL . '/admin/articles.php');
    exit;
}

$articles   = admin_get_articles(ADMIN_PER_PAGE, $offset, $search, $status);
$page_title = 'Articles';

require __DIR__ . '/admin_header.php';
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search articles…" value="<?= h($search) ?>" style="width:220px;">
                <select name="status" class="form-select form-select-sm" style="width:130px;">
                    <option value="">All statuses</option>
                    <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft"     <?= $status === 'draft'     ? 'selected' : '' ?>>Draft</option>
                    <option value="archived"  <?= $status === 'archived'  ? 'selected' : '' ?>>Archived</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                <?php if ($search || $status): ?>
                <a href="<?= SITE_URL ?>/admin/articles.php" class="btn btn-sm btn-outline-danger">Clear</a>
                <?php endif; ?>
            </form>
            <a href="<?= SITE_URL ?>/admin/article_edit.php" class="btn btn-sm" style="background:var(--accent);color:#fff;">
                <i class="bi bi-plus-lg me-1"></i>New Article
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table admin-table mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3" style="width:35%">Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">No articles found.</td></tr>
                <?php endif; ?>
                <?php foreach ($articles as $a): ?>
                <tr>
                    <td class="ps-3" style="font-size:.875rem;">
                        <strong><?= h(substr($a['title'], 0, 60)) ?><?= strlen($a['title']) > 60 ? '…' : '' ?></strong>
                    </td>
                    <td style="font-size:.8rem;"><?= h($a['category_name']) ?></td>
                    <td style="font-size:.8rem;"><?= h($a['author_name']) ?></td>
                    <td><span class="badge badge-<?= h($a['status']) ?>"><?= ucfirst($a['status']) ?></span></td>
                    <td style="font-size:.8rem;"><?= number_format($a['views']) ?></td>
                    <td style="font-size:.75rem;color:#6c757d;"><?= format_date($a['created_at']) ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <?php if ($a['status'] === 'published'): ?>
                            <a href="<?= SITE_URL ?>/article.php?slug=<?= h($a['slug']) ?>" target="_blank"
                               class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:.72rem;" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?= SITE_URL ?>/admin/article_edit.php?id=<?= $a['id'] ?>"
                               class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.72rem;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" onsubmit="return confirm('Delete this article permanently?');" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="delete_id" value="<?= $a['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.72rem;" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
        <small class="text-muted">Showing <?= ($offset + 1) ?>–<?= min($offset + ADMIN_PER_PAGE, $total) ?> of <?= $total ?></small>
        <ul class="pagination pagination-sm mb-0">
            <?php for ($p = 1; $p <= $pages; $p++): ?>
            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&status=<?= h($status) ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
