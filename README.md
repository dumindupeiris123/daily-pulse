# The Daily Pulse

A PHP + Bootstrap news website with a full admin panel, built on MariaDB.

## Requirements

- PHP 8.1+
- MariaDB / MySQL 8+
- A web server or PHP's built-in dev server

## 1. Database Setup

### Run the migration (creates the database and all tables)

```bash
mysql -u root < sql/migration.sql
```

> If your MySQL server uses a socket instead of TCP, specify it:
>
> ```bash
> mysql -u root -S /path/to/mysql.sock < sql/migration.sql
> ```

### Seed sample data (categories, articles, users, comments, tags)

```bash
mysql -u root < sql/seed.sql
```

### Default admin credentials (created by seed)

| Username | Password   | Role   |
|----------|-----------|--------|
| `admin`  | `Admin@123`  | Admin  |
| `editor` | `Editor@123` | Editor |
| `author` | `Author@123` | Author |

## 2. Configure the Application

Open `src/includes/config.php` and update the database settings to match your environment:

```php
define('DB_HOST',   'localhost');
define('DB_SOCKET', '/var/run/mysqld/mysqld.sock'); // or remove if using TCP
define('DB_NAME',   'news_db');
define('DB_USER',   'root');
define('DB_PASS',   '');                            // set your password here
```

Also update the site URL to match where you serve from:

```php
define('SITE_URL', 'http://localhost:8181');
```

## 3. Start the Development Server

```bash
php -S localhost:8181 -t src/
```

The site is now accessible at **http://localhost:8181**

## 4. Accessing the Site

### Public pages

| Page     | URL                                              |
|----------|--------------------------------------------------|
| Home     | http://localhost:8181/index.php                  |
| Category | http://localhost:8181/category.php?slug=technology |
| Article  | http://localhost:8181/article.php?slug=\<slug\>  |
| About    | http://localhost:8181/about.php                  |

### Admin panel

| Page       | URL                                          |
|------------|----------------------------------------------|
| Login      | http://localhost:8181/admin/login.php        |
| Dashboard  | http://localhost:8181/admin/dashboard.php    |
| Articles   | http://localhost:8181/admin/articles.php     |
| Categories | http://localhost:8181/admin/categories.php   |
| Comments   | http://localhost:8181/admin/comments.php     |
| Users      | http://localhost:8181/admin/users.php        |

## Project Structure

```
sql/
  migration.sql        # Database schema (run first)
  seed.sql             # Sample data

src/
  index.php            # Homepage
  category.php         # Category listing
  article.php          # Article detail + comments
  about.php            # About page

  includes/
    config.php         # Database & app settings  ← edit this
    db.php             # PDO connection
    auth.php           # Session auth & CSRF
    functions.php      # All database operations
    header.php         # Shared site header
    footer.php         # Shared site footer

  admin/
    login.php          # Admin login
    logout.php         # Session logout
    dashboard.php      # Stats overview
    articles.php       # Articles list (search, filter, delete)
    article_edit.php   # Create / edit article
    categories.php     # Categories CRUD
    comments.php       # Approve / delete comments
    users.php          # User management (admin only)
```

## Database Tables

| Table          | Purpose                          |
|----------------|----------------------------------|
| `users`        | Admin, editor and author accounts |
| `categories`   | Article categories               |
| `articles`     | News articles with status        |
| `comments`     | Reader comments (moderated)      |
| `tags`         | Article tags                     |
| `article_tags` | Many-to-many article ↔ tag pivot |

## Notes

- Change all default passwords immediately before deploying to any shared environment.
- The `migration.sql` starts with `DROP DATABASE IF EXISTS news_db` — **do not run it against a database with data you want to keep**.
- Unauthenticated visits to any `/admin/*` page redirect to the login screen automatically.
