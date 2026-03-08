# The Daily Pulse

A PHP + Bootstrap news website with a full admin panel.

## Requirements

- PHP 8.1+
- MySQL 8+

## Database Setup

Two SQL files are provided in the `sql/` folder:

- **`migration.sql`** — Creates the `news_db` database and all tables. Run this first.
- **`seed.sql`** — Populates the database with sample categories, articles, users, comments and tags. Run this after the migration.

Import both files into your MySQL server in order. You can do this through phpMyAdmin, MySQL Workbench, TablePlus, or any other database client.

### Default credentials (created by seed)

| Username | Password | Role |
|----------|----------|------|
| `admin` | `Admin@123` | Admin |
| `editor` | `Editor@123` | Editor |
| `author` | `Author@123` | Author |

## Configuration

Open `src/includes/config.php` and update the database connection settings to match your environment (host, socket path, username, password).

Also update `SITE_URL` to match the address you are serving the site from.

## Serving the Site

Point your web server's document root to the `src/` directory, or use PHP's built-in development server with `src/` as the root.

The admin panel is accessible at `/admin/login.php`.
