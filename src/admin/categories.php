<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } elseif (isset($_POST['delete_id'])) {
        $del_id = (int) $_POST['delete_id'];
        if (admin_delete_category($del_id)) {
            flash('success', 'Category deleted.');
        } else {
            flash('danger', 'Cannot delete category with existing articles. Reassign articles first.');
        }
        header('Location: ' . SITE_URL . '/admin/categories.php');
        exit;
    } elseif (isset($_POST['edit_id'])) {
        $edit_id     = (int) $_POST['edit_id'];
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (empty($name)) {
            $errors[] = 'Category name is required.';
        } else {
            admin_update_category($edit_id, $name, $description);
            flash('success', 'Category updated.');
            header('Location: ' . SITE_URL . '/admin/categories.php');
            exit;
        }
    } else {
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if (empty($name)) {
            $errors[] = 'Category name is required.';
        } else {
            admin_create_category($name, $description);
            flash('success', 'Category created.');
            header('Location: ' . SITE_URL . '/admin/categories.php');
            exit;
        }
    }
}

$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editing = $edit_id ? get_category_by_id($edit_id) : null;

$categories = get_all_categories();
$page_title = 'Categories';

require __DIR__ . '/admin_header.php';
?>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-2">
                <strong style="font-size:.85rem;"><?= $editing ? 'Edit Category' : 'New Category' ?></strong>
            </div>
            <div class="card-body">
                <?php if ($errors): ?>
                <div class="alert alert-danger" style="font-size:.875rem;"><?= implode('<br>', array_map('h', $errors)) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <?php if ($editing): ?>
                    <input type="hidden" name="edit_id" value="<?= $editing['id'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= h($editing['name'] ?? ($_POST['name'] ?? '')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= h($editing['description'] ?? ($_POST['description'] ?? '')) ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm" style="background:var(--accent);color:#fff;">
                            <?= $editing ? 'Update' : 'Create' ?>
                        </button>
                        <?php if ($editing): ?>
                        <a href="<?= SITE_URL ?>/admin/categories.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table admin-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Slug</th>
                            <th>Articles</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr <?= $editing && $editing['id'] === $cat['id'] ? 'class="table-warning"' : '' ?>>
                            <td class="ps-3 fw-semibold" style="font-size:.875rem;"><?= h($cat['name']) ?></td>
                            <td style="font-size:.75rem;color:#6c757d;font-family:monospace;"><?= h($cat['slug']) ?></td>
                            <td><span class="badge bg-secondary"><?= $cat['article_count'] ?></span></td>
                            <td style="font-size:.8rem;max-width:200px;"><?= h(substr($cat['description'] ?? '', 0, 60)) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="?edit=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.72rem;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($cat['article_count'] == 0): ?>
                                    <form method="POST" onsubmit="return confirm('Delete category &quot;<?= h(addslashes($cat['name'])) ?>&quot;?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="delete_id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.72rem;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.72rem;" disabled title="Has articles">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
