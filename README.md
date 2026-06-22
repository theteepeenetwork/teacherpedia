# Teacherpedia

Teacherpedia is a teacher educational-resources website built on
[CodeIgniter 4](https://codeigniter.com/) (PHP). It is a server-rendered
MVC application backed by a MySQL/MariaDB database. The web root is
`public_html/` and the application code lives in `ci4/`.

## Requirements

- PHP 8.x with the `mysqli`, `intl`, and `mbstring` extensions
- [Composer](https://getcomposer.org/)
- MySQL or MariaDB

## Setup

1. Install dependencies:

   ```bash
   cd public_html
   composer install
   ```

2. Create your environment file by copying the template and filling in
   your database credentials:

   ```bash
   cp .env.example ci4/.env
   ```

   Edit `ci4/.env` and set the `database.development.*` values.

3. Create the database in MySQL/MariaDB (matching the name in your
   `.env`, e.g. `teacherpedia`).

4. Run the migrations:

   ```bash
   cd ci4
   php spark migrate
   ```

5. Seed sample data using the main seeder (seeder classes live under
   `ci4/app/Database/Seeds`):

   ```bash
   php spark db:seed DatabaseSeeder
   ```

6. Run the app locally:

   ```bash
   cd ci4
   php spark serve
   ```

   Then open the URL printed in the terminal. Alternatively, point
   Apache's document root at `public_html/`.

## Security note

The git history of this repository contains previously-committed live
database passwords for the production and test hosts. **These
credentials must be rotated on the hosting provider.** Rotating the
credentials and purging git history cannot be done from the codebase
alone, so this is a required manual operational step.

Database credentials are now read from `ci4/.env` via CodeIgniter's
`env()` helper and must never be committed to version control.

## Project layout

- `ci4/app/Controllers` — request handlers / route targets
- `ci4/app/Models` — database models
- `ci4/app/Views` — server-rendered templates
- `ci4/app/Config` — application configuration (including `Database.php`)
- `ci4/app/Database/Migrations` — schema migrations
- `ci4/app/Database/Seeds` — database seeders
- `public_html/` — web root and front-end assets
