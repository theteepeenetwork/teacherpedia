# Teacherpedia — Upgrade & Maintenance Notes

This document records the June 2026 modernisation of the codebase and the
recommended follow-up work.

## What changed

### Framework: CodeIgniter 4.0.3 → 4.7.3 (Composer-managed)
- The bundled, end-of-life `ci4/system` (CI 4.0.3, PHP 7.2–7.4 only) was replaced
  with a Composer-managed `codeigniter4/framework ^4.7`. The app now runs cleanly
  on **PHP 8.4**.
- The unused, abandoned `myth/auth` dependency was removed (authentication is fully
  custom — see `app/Filters/Auth.php`, `Login_model`, `Admin_login_model`).
- 4.7.3 also resolves two **critical** CVEs that affect the 4.5 line:
  `CVE-2026-48062` (file-upload `ext_in` bypass) and `CVE-2025-54418`
  (ImageMagick command injection). `composer audit` is clean.

### Project layout (standard CI4)
```
app/                      application code (was ci4/app)
  Views/resources_generated/   single canonical home for generated resources
vendor/codeigniter4/...   framework (was ci4/system)
public_html/             web docroot (MAMP) — index.php + assets/images only
writable/                cache, logs, sessions, uploads, deleted/
spark, composer.json     project root
.env                     CI_ENVIRONMENT + app/db overrides (gitignored)
```

### Resource pipeline ("auto-generated" worksheets)
- Generated resources (admin-authored `index.php` + `generator.php` + supports such
  as `Number.php`) were scattered across `/resources`, `/resources1`,
  `/public_html/resources` and `/test_resources`. They are now consolidated under
  **`app/Views/resources_generated/`** and rendered through the view layer, so they
  are no longer directly web-served (reduces the remote-code-execution surface).
- `Admin\Resources::do_uploads()` was rewritten: it writes each code file once to the
  canonical directory, uploads banner/thumb images to `public_html/assets/...`,
  records keywords, and inserts the row — the resource then auto-generates on the
  public site immediately.
- The parallel "test resources" feature was retired.
- DB migration `2026-06-23-100000_MigrateResourceLinks` rewrites the stored `link`
  prefix; `ResourcesModel::viewBase()` also normalises legacy paths at runtime.

## Running locally (MAMP)

**Requirements:** PHP **8.2+** (CodeIgniter 4.7 will not run on PHP 7.x/8.0/8.1).
In MAMP, set Preferences → PHP → Standard version to 8.2 or newer, and point the
Apache document root at this project's `public_html/` folder.

### 1. Install the framework (one time, and after every `git pull`)
`vendor/` is intentionally **not** committed, so the framework must be installed
with Composer or you will get:
`Failed to open stream: .../vendor/codeigniter4/framework/system/Boot.php`.

If you have a global `composer`:
```bash
cd /path/to/teacherpedia
composer install
```

If you don't, use MAMP's PHP with a local `composer.phar` (adjust the php8.2.0
folder to match your installed MAMP PHP version):
```bash
cd /path/to/teacherpedia
PHP=/Applications/MAMP/bin/php/php8.2.0/bin/php
"$PHP" -r "copy('https://getcomposer.org/installer','composer-setup.php');"
"$PHP" composer-setup.php && rm composer-setup.php
"$PHP" composer.phar install
```
Tip: if `php` on your shell PATH is not 8.2+, prefix spark/composer commands with the
full MAMP PHP path above (e.g. `"$PHP" spark routes`).

### 2. Configure
- `.env` (repo root) should contain `CI_ENVIRONMENT = development`.
- The `development` DB group in `app/Config/Database.php` targets MySQL at
  `localhost:8889` (`root`/`root`, db `teacherpedia`) — adjust to match your MAMP.
- Base URL is `http://localhost:8888/` (`app/Config/App.php`).

### 3. Migrate & smoke test
```bash
php spark migrate    # normalise the resource `link` column (one time)
php spark routes     # should print the routes table with no fatal
```
Then browse `/`, open a resource at `/resource/<slug>`, and submit a worksheet form
to confirm it auto-generates.

## Recommended follow-ups (not yet done)
1. **Enable CSRF protection.** It is currently off because the existing forms (login,
   register, contact, add/edit resource, gallery) do not yet send tokens. Enable
   `csrf` in `app/Config/Filters.php` globals and add `<?= csrf_field() ?>` to each
   `<form>`.
2. **Rotate and externalise DB credentials.** `app/Config/Database.php` contains live
   production hosting credentials in source control. Move all DB creds to `.env`
   (`database.default.*`) and rotate the exposed passwords.
3. **Content Security Policy.** CSP is disabled and the per-controller CSP source
   lists in `BaseController`/`Admin_Controller` are hardcoded to localhost. If CSP is
   wanted, move the allowed-host lists to config/env and enable `CSPEnabled`. Note the
   app uses many inline scripts/handlers, so `unsafe-inline` or nonces would be needed.
4. **Encryption key** in `app/Config/Encryption.php` should be moved to `.env`
   (`encryption.key`) and set to a 32-byte key.
5. **Auto-routing.** The app relies on legacy auto-routing
   (`Config\Routing::$autoRoute = true`, `Config\Feature::$autoRoutesImproved = false`).
   Migrating to explicit routes (or "Auto Routing (Improved)") would tighten which
   controller methods are web-reachable.
6. **Resource authoring is raw PHP.** `do_uploads` lets admins write executable PHP.
   It is gated behind the admin `auth` filter and kept out of the docroot; treat admin
   access as privileged and consider a safer templating approach long-term.
