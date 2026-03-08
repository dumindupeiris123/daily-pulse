<?php
require_once __DIR__ . '/db.php';

// ─── Sanitization & Output ────────────────────────────────────────────────────

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

function time_ago(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)       return 'just now';
    if ($diff < 3600)     return floor($diff / 60) . 'm ago';
    if ($diff < 86400)    return floor($diff / 3600) . 'h ago';
    if ($diff < 604800)   return floor($diff / 86400) . 'd ago';
    return date('M j, Y', strtotime($datetime));
}

function format_date(string $datetime, string $format = 'M j, Y'): string {
    return date($format, strtotime($datetime));
}

function excerpt(string $text, int $words = 20): string {
    $arr = explode(' ', strip_tags($text));
    if (count($arr) <= $words) return strip_tags($text);
    return implode(' ', array_slice($arr, 0, $words)) . '…';
}

function flash(string $type, string $message): void {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes(): array {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

// ─── Articles ─────────────────────────────────────────────────────────────────

function get_published_articles(int $limit = 6, int $offset = 0, ?int $category_id = null): array {
    $db = get_db();
    $sql = 'SELECT a.*, c.name AS category_name, c.slug AS category_slug,
                   u.username AS author_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN users u ON a.author_id = u.id
            WHERE a.status = "published"';
    $params = [];
    if ($category_id !== null) {
        $sql .= ' AND a.category_id = ?';
        $params[] = $category_id;
    }
    $sql .= ' ORDER BY a.published_at DESC LIMIT ? OFFSET ?';
    $params[] = $limit;
    $params[] = $offset;
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function count_published_articles(?int $category_id = null): int {
    $db = get_db();
    $sql = 'SELECT COUNT(*) FROM articles WHERE status = "published"';
    $params = [];
    if ($category_id !== null) {
        $sql .= ' AND category_id = ?';
        $params[] = $category_id;
    }
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function get_article_by_slug(string $slug): ?array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT a.*, c.name AS category_name, c.slug AS category_slug,
                u.username AS author_name
         FROM articles a
         JOIN categories c ON a.category_id = c.id
         JOIN users u ON a.author_id = u.id
         WHERE a.slug = ? LIMIT 1'
    );
    $stmt->execute([$slug]);
    $article = $stmt->fetch();
    return $article ?: null;
}

function get_article_by_id(int $id): ?array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT a.*, c.name AS category_name, u.username AS author_name
         FROM articles a
         JOIN categories c ON a.category_id = c.id
         JOIN users u ON a.author_id = u.id
         WHERE a.id = ? LIMIT 1'
    );
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    return $article ?: null;
}

function increment_views(int $id): void {
    $db = get_db();
    $db->prepare('UPDATE articles SET views = views + 1 WHERE id = ?')->execute([$id]);
}

function get_related_articles(int $article_id, int $category_id, int $limit = 3): array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT a.*, c.name AS category_name, c.slug AS category_slug
         FROM articles a
         JOIN categories c ON a.category_id = c.id
         WHERE a.category_id = ? AND a.id != ? AND a.status = "published"
         ORDER BY a.published_at DESC LIMIT ?'
    );
    $stmt->execute([$category_id, $article_id, $limit]);
    return $stmt->fetchAll();
}

function get_trending_articles(int $limit = 5): array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT a.*, c.name AS category_name, c.slug AS category_slug
         FROM articles a
         JOIN categories c ON a.category_id = c.id
         WHERE a.status = "published"
         ORDER BY a.views DESC LIMIT ?'
    );
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// ─── Categories ───────────────────────────────────────────────────────────────

function get_all_categories(): array {
    $db = get_db();
    $stmt = $db->query(
        'SELECT c.*, COUNT(a.id) AS article_count
         FROM categories c
         LEFT JOIN articles a ON c.id = a.category_id AND a.status = "published"
         GROUP BY c.id
         ORDER BY c.name'
    );
    return $stmt->fetchAll();
}

function get_category_by_slug(string $slug): ?array {
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM categories WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $cat = $stmt->fetch();
    return $cat ?: null;
}

function get_category_by_id(int $id): ?array {
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM categories WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $cat = $stmt->fetch();
    return $cat ?: null;
}

// ─── Comments ─────────────────────────────────────────────────────────────────

function get_approved_comments(int $article_id): array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT * FROM comments WHERE article_id = ? AND is_approved = 1 ORDER BY created_at ASC'
    );
    $stmt->execute([$article_id]);
    return $stmt->fetchAll();
}

function submit_comment(int $article_id, string $name, string $email, string $content): bool {
    $db = get_db();
    $stmt = $db->prepare(
        'INSERT INTO comments (article_id, name, email, content, is_approved, created_at)
         VALUES (?, ?, ?, ?, 0, NOW())'
    );
    return $stmt->execute([$article_id, $name, $email, $content]);
}

// ─── Tags ─────────────────────────────────────────────────────────────────────

function get_article_tags(int $article_id): array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT t.* FROM tags t
         JOIN article_tags at ON t.id = at.tag_id
         WHERE at.article_id = ?'
    );
    $stmt->execute([$article_id]);
    return $stmt->fetchAll();
}

// ─── Admin: Articles ──────────────────────────────────────────────────────────

function admin_get_articles(int $limit = 10, int $offset = 0, string $search = '', string $status = ''): array {
    $db = get_db();
    $params = [];
    $where = [];
    if ($search !== '') {
        $where[] = '(a.title LIKE ? OR a.excerpt LIKE ?)';
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($status !== '') {
        $where[] = 'a.status = ?';
        $params[] = $status;
    }
    $whereStr = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $sql = "SELECT a.*, c.name AS category_name, u.username AS author_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN users u ON a.author_id = u.id
            $whereStr
            ORDER BY a.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function admin_count_articles(string $search = '', string $status = ''): int {
    $db = get_db();
    $params = [];
    $where = [];
    if ($search !== '') {
        $where[] = '(title LIKE ? OR excerpt LIKE ?)';
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    if ($status !== '') {
        $where[] = 'status = ?';
        $params[] = $status;
    }
    $whereStr = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $stmt = $db->prepare("SELECT COUNT(*) FROM articles $whereStr");
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function admin_create_article(array $data): int {
    $db = get_db();
    $stmt = $db->prepare(
        'INSERT INTO articles (title, slug, excerpt, content, image_url, author_id, category_id, status, published_at, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
    );
    $published_at = ($data['status'] === 'published') ? date('Y-m-d H:i:s') : null;
    $stmt->execute([
        $data['title'], $data['slug'], $data['excerpt'], $data['content'],
        $data['image_url'] ?? null, $data['author_id'], $data['category_id'],
        $data['status'], $published_at,
    ]);
    return (int) $db->lastInsertId();
}

function admin_update_article(int $id, array $data): bool {
    $db = get_db();
    // If status changed to published and no published_at yet, set it
    $current = get_article_by_id($id);
    $published_at = $current['published_at'];
    if ($data['status'] === 'published' && empty($published_at)) {
        $published_at = date('Y-m-d H:i:s');
    }
    $stmt = $db->prepare(
        'UPDATE articles SET title=?, slug=?, excerpt=?, content=?, image_url=?, category_id=?, status=?, published_at=?, updated_at=NOW()
         WHERE id=?'
    );
    return $stmt->execute([
        $data['title'], $data['slug'], $data['excerpt'], $data['content'],
        $data['image_url'] ?? null, $data['category_id'], $data['status'],
        $published_at, $id,
    ]);
}

function admin_delete_article(int $id): bool {
    $db = get_db();
    $db->prepare('DELETE FROM article_tags WHERE article_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM comments WHERE article_id = ?')->execute([$id]);
    return $db->prepare('DELETE FROM articles WHERE id = ?')->execute([$id]);
}

// ─── Admin: Categories ────────────────────────────────────────────────────────

function admin_create_category(string $name, string $description): int {
    $db = get_db();
    $slug = slugify($name);
    $stmt = $db->prepare('INSERT INTO categories (name, slug, description, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$name, $slug, $description]);
    return (int) $db->lastInsertId();
}

function admin_update_category(int $id, string $name, string $description): bool {
    $db = get_db();
    $slug = slugify($name);
    $stmt = $db->prepare('UPDATE categories SET name=?, slug=?, description=?, created_at=created_at WHERE id=?');
    return $stmt->execute([$name, $slug, $description, $id]);
}

function admin_delete_category(int $id): bool {
    $db = get_db();
    // Can't delete if articles exist
    $stmt = $db->prepare('SELECT COUNT(*) FROM articles WHERE category_id = ?');
    $stmt->execute([$id]);
    if ((int) $stmt->fetchColumn() > 0) return false;
    return $db->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
}

// ─── Admin: Users ─────────────────────────────────────────────────────────────

function admin_get_users(int $limit = 10, int $offset = 0): array {
    $db = get_db();
    $stmt = $db->prepare('SELECT id, username, email, role, is_active, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?');
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}

function admin_count_users(): int {
    return (int) get_db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
}

function admin_create_user(string $username, string $email, string $password, string $role): int {
    $db = get_db();
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare('INSERT INTO users (username, email, password_hash, role, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())');
    $stmt->execute([$username, $email, $hash, $role]);
    return (int) $db->lastInsertId();
}

function admin_update_user(int $id, string $username, string $email, string $role, int $is_active, string $password = ''): bool {
    $db = get_db();
    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare('UPDATE users SET username=?, email=?, password_hash=?, role=?, is_active=?, updated_at=NOW() WHERE id=?');
        return $stmt->execute([$username, $email, $hash, $role, $is_active, $id]);
    }
    $stmt = $db->prepare('UPDATE users SET username=?, email=?, role=?, is_active=?, updated_at=NOW() WHERE id=?');
    return $stmt->execute([$username, $email, $role, $is_active, $id]);
}

function admin_delete_user(int $id): bool {
    $db = get_db();
    // Reassign articles to admin (id=1), or prevent deletion if same user
    $db->prepare('UPDATE articles SET author_id = 1 WHERE author_id = ?')->execute([$id]);
    return $db->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
}

// ─── Admin: Comments ─────────────────────────────────────────────────────────

function admin_get_comments(int $limit = 10, int $offset = 0): array {
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT c.*, a.title AS article_title, a.slug AS article_slug
         FROM comments c
         JOIN articles a ON c.article_id = a.id
         ORDER BY c.created_at DESC LIMIT ? OFFSET ?'
    );
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}

function admin_count_comments(): int {
    return (int) get_db()->query('SELECT COUNT(*) FROM comments')->fetchColumn();
}

function admin_approve_comment(int $id): bool {
    return get_db()->prepare('UPDATE comments SET is_approved = 1 WHERE id = ?')->execute([$id]);
}

function admin_delete_comment(int $id): bool {
    return get_db()->prepare('DELETE FROM comments WHERE id = ?')->execute([$id]);
}

// ─── Dashboard stats ──────────────────────────────────────────────────────────

function get_dashboard_stats(): array {
    $db = get_db();
    return [
        'total_articles'   => (int) $db->query('SELECT COUNT(*) FROM articles')->fetchColumn(),
        'published'        => (int) $db->query('SELECT COUNT(*) FROM articles WHERE status = "published"')->fetchColumn(),
        'draft'            => (int) $db->query('SELECT COUNT(*) FROM articles WHERE status = "draft"')->fetchColumn(),
        'total_categories' => (int) $db->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
        'total_users'      => (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
        'pending_comments' => (int) $db->query('SELECT COUNT(*) FROM comments WHERE is_approved = 0')->fetchColumn(),
        'total_views'      => (int) $db->query('SELECT COALESCE(SUM(views), 0) FROM articles')->fetchColumn(),
    ];
}
