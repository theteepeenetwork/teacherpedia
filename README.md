# Teacherpedia

Teacherpedia is a UK primary‑school **printable worksheet generator**. Teachers pick
curriculum objectives (Years 3–6), and the app builds self‑marking, curriculum‑aligned
worksheets that **regenerate fresh questions on demand** — plus other activities like a
Code Breaker. Built on **CodeIgniter 4** (PHP 8.1+).

This is a ground‑up rebuild implementing the claude.ai/design system. The product model
is a **curriculum library of objectives**, each optionally backed by a JavaScript
**generator** that returns `{ question, answer }`; activities compose objectives into
printable sheets with a live preview, difficulty control, and one‑click regenerate.

## Features / routes

| Area | Route | Notes |
|------|-------|-------|
| Home | `/` | Marketing landing |
| Browse | `/browse` | Activity directory (from DB) |
| Pricing / Our vision / Privacy | `/pricing` `/vision` `/privacy` | |
| Contact | `/contact` | |
| Login / Register | `/login` `/register` | Teacher accounts |
| Worksheet builder | `/build` | Primary tool; objective library, regenerate, print, save |
| Code Breaker | `/code-breaker` | Cipher puzzle activity |
| My saved sheets | `/account` | *requires login* |
| Admin dashboard | `/admin` | *admin only* — coverage stats, submission queue |
| Admin Studio | `/admin/studio` | *admin only* — author/validate JS generators |
| Admin sign‑in | `/admin/login` | |

## Local setup

Requires PHP 8.1+ (tested on 8.4) with `intl` and `mbstring`. Local dev uses **SQLite**
(no MySQL needed).

```bash
composer install
cp env .env            # if you don't already have one; see notes below
php spark migrate
php spark db:seed DatabaseSeeder     # 217 objectives + 6 activities
php spark db:seed AdminUserSeeder    # first admin account
php spark serve                      # http://localhost:8080
```

Default seeded admin: `admin@teacherpedia.test` / `changeme123`
(override with `ADMIN_SEED_EMAIL` / `ADMIN_SEED_PASSWORD` env vars — **change in
production**).

### Running the app (and the `/browse` 404 gotcha)

The document root is **`public_html/`** (not CI4's default `public/`), and clean URLs
like `/browse`, `/build`, `/pricing` only work if the web server rewrites unknown paths
to `public_html/index.php`. If `/` loads but `/browse` returns **404**, rewriting isn't
reaching the front controller. Use one of:

- **`php spark serve --port 8888`** — recommended for local dev. Its built‑in router
  rewrites correctly, so all routes work out of the box. (Pass `--port` to match your
  `app.baseURL`.)
- **Apache** — point the vhost at `public_html/`; requires `mod_rewrite` enabled and
  `AllowOverride All` so `public_html/.htaccess` (already included) is honoured.
- **nginx** — has no `.htaccess`; use the provided `deploy/nginx.conf.sample` (its
  `try_files $uri $uri/ /index.php?$query_string;` is what fixes the 404).
- **`php -S`** — must target the docroot: `php -S localhost:8888 -t public_html`.

> The rebuilt site (Browse and the other new pages) lives on the rebuild branch. If you
> see `/browse` 404 on an older checkout, `git checkout` that branch (or merge the PR) —
> `main` predates these routes.

### Configuration / secrets

- The dev `default` database group is SQLite at `writable/db/teacherpedia.db`.
- **Production/test MySQL credentials live in `.env`** (`database.production.*` /
  `database.tests.*`) — they are **no longer committed** in `app/Config/Database.php`.
  Set `CI_ENVIRONMENT = production` and the `database.production.*` keys to deploy.
- `.env` is gitignored.

## Architecture

- **Controllers** `app/Controllers` — `Home`, `Browse`, `Pages`, `Contact`, `Auth`,
  `Build`, `CodeBreaker`, `Account`, `Admin\Admin`, `Admin\Studio`.
- **Models** `app/Models` — `ObjectiveModel`, `ActivityModel`, `SavedSheetModel`,
  `SubmissionModel`, plus `Login_model` / `Admin_login_model` (auth).
- **Layouts** `app/Views/layouts/{public,app}.php`; pages under `app/Views/*`.
- **Assets** `public_html/assets/css/teacherpedia.css` (design tokens + components),
  `tp-print.css`, and `assets/js/` (`tp-generators.js` = 160 question generators +
  `TP_generate(key, difficulty)`, plus per‑page scripts).
- **Routing** is explicit (auto‑routing disabled). `/account` is gated by the `auth`
  filter, `/admin/*` by the `admin` filter.

The web server document root is `public_html/`.
