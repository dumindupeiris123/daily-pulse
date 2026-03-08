<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } elseif (isset($_POST['delete_id'])) {
        $del_id = (int) $_POST['delete_id'];
        if ($del_id === (int) current_user()['id']) {
            flash('danger', 'You cannot delete your own account.');
        } else {
            admin_delete_user($del_id);
            flash('success', 'User deleted.');
        }
        header('Location: ' . SITE_URL . '/admin/users.php');
        exit;
    } elseif (isset($_POST['edit_id'])) {
        $edit_id   = (int) $_POST['edit_id'];
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $role      = $_POST['role'] ?? 'author';
        $is_active = (int) ($_POST['is_active'] ?? 1);
        $password  = $_POST['password'] ?? '';

        if (empty($username))                      $errors[] = 'Username is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';

        if (empty($errors)) {
            admin_update_user($edit_id, $username, $email, $role, $is_active, $password);
            flash('success', 'User updated.');
            header('Location: ' . SITE_URL . '/admin/users.php');
            exit;
        }
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role     = $_POST['role'] ?? 'author';

        if (empty($username))                       $errors[] = 'Username is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $errors[] = 'Valid email is required.';
        if (strlen($password) < 8)                  $errors[] = 'Password must be at least 8 characters.';

        if (empty($errors)) {
            admin_create_user($username, $email, $password, $role);
            flash('success', 'User created.');
            header('Location: ' . SITE_URL . '/admin/users.php');
            exit;
        }
    }
}

$edit_id  = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editing  = null;
if ($edit_id) {
    $db = get_db();
    $stmt = $db->prepare('SELECT id, username, email, role, is_active FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$edit_id]);
    $editing = $stmt->fetch() ?: null;
}

$page     = max(1, (int) ($_GET['page'] ?? 1));
$offset   = ($page - 1) * ADMIN_PER_PAGE;
$total    = admin_count_users();
$pages    = (int) ceil($total / ADMIN_PER_PAGE);
$users    = admin_get_users(ADMIN_PER_PAGE, $offset);

$page_title = 'Users';
require __DIR__ . '/admin_header.php';
?>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-2">
                <strong style="font-size:.85rem;"><?= $editing ? 'Edit User' : 'New User' ?></strong>
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
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control form-control-sm"
                               value="<?= h($editing['username'] ?? ($_POST['username'] ?? '')) ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm"
                               value="<?= h($editing['email'] ?? ($_POST['email'] ?? '')) ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">
                            Password <?= $editing ? '<span class="text-muted">(leave blank to keep)</span>' : '<span class="text-danger">*</span>' ?>
                        </label>
                        <input type="password" name="password" class="form-control form-control-sm"
                               <?= $editing ? '' : 'required' ?> autocomplete="new-password">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Role</label>
                        <select name="role" class="form-select form-select-sm">
                            <option value="author" <?= ($editing['role'] ?? 'author') === 'author' ? 'selected' : '' ?>>Author</option>
                            <option value="editor" <?= ($editing['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
                            <option value="admin"  <?= ($editing['role'] ?? '') === 'admin'  ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <?php if ($editing): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:.85rem;">Status</label>
                        <select name="is_active" class="form-select form-select-sm">
                            <option value="1" <?= ($editing['is_active'] ?? 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= !($editing['is_active'] ?? 1) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm" style="background:var(--accent);color:#fff;">
                            <?= $editing ? 'Update User' : 'Create User' ?>
                        </button>
                        <?php if ($editing): ?>
                        <a href="<?= SITE_URL ?>/admin/users.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
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
                            <th class="ps-3">Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr <?= $editing && $editing['id'] === $u['id'] ? 'class="table-warning"' : '' ?>>
                            <td class="ps-3 fw-semibold" style="font-size:.875rem;"><?= h($u['username']) ?></td>
                            <td style="font-size:.8rem;"><?= h($u['email']) ?></td>
                            <td><span class="badge badge-<?= h($u['role']) ?>"><?= ucfirst($u['role']) ?></span></td>
                            <td>
                                <span class="badge <?= $u['is_active'] ? 'badge-published' : 'bg-secondary' ?>">
                                    <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td style="font-size:.75rem;color:#6c757d;"><?= format_date($u['created_at']) ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.72rem;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($u['id'] !== (int) current_user()['id']): ?>
                                    <form method="POST" onsubmit="return confirm('Delete user &quot;<?= h(addslashes($u['username'])) ?>&quot;?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.72rem;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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
