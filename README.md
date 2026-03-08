# The Daily Pulse

## Requirements

- PHP 8.1+
- MySQL 8+

## Database Setup

- **`migration.sql`** — Creates the `news_db` database and all tables. Run this first.
- **`seed.sql`** — Populates the database with sample categories, articles, users, comments and tags. Run this after the migration.

### Default credentials (created by seed)

| Username | Password | Role |
|----------|----------|------|
| `admin` | `Admin@123` | Admin |
| `editor` | `Editor@123` | Editor |
| `author` | `Author@123` | Author |


## Configuration

Update `src/includes/config.php` with database connection settings.
Also update `SITE_URL` to match the address.
