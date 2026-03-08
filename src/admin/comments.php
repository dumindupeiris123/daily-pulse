<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('danger', 'Invalid security token.');
    } elseif (isset($_POST['approve_id'])) {
        admin_approve_comment((int) $_POST['approve_id']);
        flash('success', 'Comment approved.');
    } elseif (isset($_POST['delete_id'])) {
        admin_delete_comment((int) $_POST['delete_id']);
        flash('success', 'Comment deleted.');
    }
    header('Location: ' . SITE_URL . '/admin/comments.php');
    exit;
}

$page     = max(1, (int) ($_GET['page'] ?? 1));
$offset   = ($page - 1) * ADMIN_PER_PAGE;
$total    = admin_count_comments();
$pages    = (int) ceil($total / ADMIN_PER_PAGE);
$comments = admin_get_comments(ADMIN_PER_PAGE, $offset);

$page_title = 'Comments';
require __DIR__ . '/admin_header.php';
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table admin-table mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Author</th>
                    <th>Comment</th>
                    <th>Article</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($comments)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No comments yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($comments as $c): ?>
                <tr class="<?= !$c['is_approved'] ? 'table-warning' : '' ?>">
                    <td class="ps-3">
                        <strong style="font-size:.875rem;"><?= h($c['name']) ?></strong>
                        <div style="font-size:.75rem;color:#6c757d;"><?= h($c['email']) ?></div>
                    </td>
                    <td style="font-size:.85rem;max-width:280px;"><?= h(substr($c['content'], 0, 100)) ?><?= strlen($c['content']) > 100 ? '…' : '' ?></td>
                    <td style="font-size:.8rem;">
                        <a href="<?= SITE_URL ?>/src/article.php?slug=<?= h($c['article_slug']) ?>" target="_blank" class="text-decoration-none">
                            <?= h(substr($c['article_title'], 0, 40)) ?>…
                        </a>
                    </td>
                    <td>
                        <?php if ($c['is_approved']): ?>
                        <span class="badge badge-published">Approved</span>
                        <?php else: ?>
                        <span class="badge badge-draft">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.75rem;color:#6c757d;"><?= format_date($c['created_at'], 'M j, Y') ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <?php if (!$c['is_approved']): ?>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="approve_id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size:.72rem;" title="Approve">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" onsubmit="return confirm('Delete this comment?');">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
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
        <small class="text-muted">Showing <?= $offset + 1 ?>–<?= min($offset + ADMIN_PER_PAGE, $total) ?> of <?= $total ?></small>
        <ul class="pagination pagination-sm mb-0">
            <?php for ($p = 1; $p <= $pages; $p++): ?>
            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
