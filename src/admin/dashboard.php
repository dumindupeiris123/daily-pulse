<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();

$page_title = 'Dashboard';
$stats      = get_dashboard_stats();
$recent     = admin_get_articles(5, 0);

require __DIR__ . '/admin_header.php';
?>

<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['bi-newspaper',   'var(--accent)',  $stats['total_articles'],   'Total Articles'],
        ['bi-check-circle','#10b981',        $stats['published'],        'Published'],
        ['bi-pencil-square','#f59e0b',       $stats['draft'],            'Drafts'],
        ['bi-tag',         '#6366f1',        $stats['total_categories'], 'Categories'],
        ['bi-people',      '#0ea5e9',        $stats['total_users'],      'Users'],
        ['bi-chat-dots',   '#ec4899',        $stats['pending_comments'], 'Pending Comments'],
        ['bi-eye',         '#8b5cf6',        number_format($stats['total_views']), 'Total Views'],
    ];
    foreach ($cards as $c): ?>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:<?= $c[1] ?>22;">
                    <i class="bi <?= $c[0] ?>" style="color:<?= $c[1] ?>;"></i>
                </div>
                <div>
                    <div class="stat-value"><?= $c[2] ?></div>
                    <div class="stat-label"><?= $c[3] ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <strong style="font-size:.875rem;">Recent Articles</strong>
                <a href="<?= SITE_URL ?>/src/admin/articles.php" style="font-size:.8rem;color:var(--accent);">View all →</a>
            </div>
            <div class="card-body p-0">
                <table class="table admin-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent as $a): ?>
                        <tr>
                            <td class="ps-3" style="font-size:.875rem;max-width:280px;">
                                <strong><?= h(substr($a['title'], 0, 55)) ?><?= strlen($a['title']) > 55 ? '…' : '' ?></strong>
                            </td>
                            <td style="font-size:.8rem;"><?= h($a['category_name']) ?></td>
                            <td style="font-size:.8rem;"><?= h($a['author_name']) ?></td>
                            <td>
                                <span class="badge badge-<?= h($a['status']) ?>"><?= ucfirst($a['status']) ?></span>
                            </td>
                            <td style="font-size:.8rem;"><?= number_format($a['views']) ?></td>
                            <td style="font-size:.75rem;color:#6c757d;"><?= format_date($a['created_at']) ?></td>
                            <td>
                                <a href="<?= SITE_URL ?>/src/admin/article_edit.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:.75rem;">Edit</a>
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
