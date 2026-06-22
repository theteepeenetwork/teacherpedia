#!/usr/bin/env bash
#
# Idempotent local development setup for Teacherpedia (CodeIgniter 4).
#
# - Installs and starts MariaDB if it is not already available
# - Creates the `teacherpedia` database and a local app user
# - Runs database migrations and seeds sample data
#
# Safe to run repeatedly. Intended for local/dev and ephemeral cloud
# environments — NOT for production.
#
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DB_NAME="teacherpedia"
DB_USER="teacherpedia"
DB_PASS="teacherpedia"

echo "==> Teacherpedia dev setup"

# 1. Ensure MariaDB is installed.
if ! command -v mariadbd >/dev/null 2>&1; then
  echo "==> Installing MariaDB server..."
  sudo apt-get update -y
  sudo apt-get install -y mariadb-server mariadb-client
fi

# 2. Ensure the data directory is initialised and the server is running.
sudo install -d -o mysql -g mysql /var/lib/mysql /run/mysqld
if [ ! -d /var/lib/mysql/mysql ]; then
  echo "==> Initialising MariaDB data directory..."
  sudo mariadb-install-db --user=mysql --datadir=/var/lib/mysql --auth-root-authentication-method=normal >/dev/null
fi
if ! sudo mariadb-admin ping >/dev/null 2>&1; then
  echo "==> Starting MariaDB..."
  sudo mariadbd --user=mysql >/tmp/mariadbd.log 2>&1 &
  for _ in $(seq 1 30); do
    sudo mariadb-admin ping >/dev/null 2>&1 && break
    sleep 1
  done
fi

# 3. Create database and application user.
echo "==> Ensuring database '${DB_NAME}' and user '${DB_USER}' exist..."
sudo mariadb <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'127.0.0.1';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL

# 4. Ensure ci4/.env exists (fall back to the template).
if [ ! -f "${REPO_ROOT}/ci4/.env" ] && [ -f "${REPO_ROOT}/.env.example" ]; then
  echo "==> Creating ci4/.env from .env.example..."
  cp "${REPO_ROOT}/.env.example" "${REPO_ROOT}/ci4/.env"
fi

# 5. Run migrations (idempotent) and seed sample data only when empty.
echo "==> Running migrations..."
( cd "${REPO_ROOT}/ci4" && php spark migrate )

RESOURCE_COUNT="$(sudo mariadb -N -B "${DB_NAME}" -e "SELECT COUNT(*) FROM resources;" 2>/dev/null || echo 0)"
if [ "${RESOURCE_COUNT:-0}" -eq 0 ]; then
  echo "==> Seeding sample data..."
  ( cd "${REPO_ROOT}/ci4" && php spark db:seed DatabaseSeeder )
else
  echo "==> Sample data already present (${RESOURCE_COUNT} resources); skipping seed."
fi

cat <<DONE

==> Done. Start the app with:
      cd ${REPO_ROOT}/ci4 && php spark serve --host 127.0.0.1 --port 8080
    then open http://localhost:8080/

    Seeded admin login:  admin@teacherpedia.test / Password123!
DONE
