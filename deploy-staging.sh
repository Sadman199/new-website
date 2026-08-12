#!/bin/bash
# Deploy BrokersCourt staging branch to staging.brokerscourt.com
# Run from cPanel Terminal or SSH after subdomain is created.
# Does NOT touch production (public_html / brokerscourt.com).

set -euo pipefail

STAGING_DIR="${HOME}/staging.brokerscourt.com"
REPO="https://github.com/coderbiozed/brokerscourt.git"
BRANCH="staging"

echo "==> BrokersCourt staging deploy"
echo "    Target: ${STAGING_DIR}"
echo "    Branch: ${BRANCH}"
echo ""

if [ ! -d "${STAGING_DIR}/.git" ]; then
    echo "==> Cloning repo..."
    git clone "${REPO}" "${STAGING_DIR}"
    cd "${STAGING_DIR}"
    git checkout "${BRANCH}"
else
    echo "==> Pulling latest ${BRANCH}..."
    cd "${STAGING_DIR}"
    git fetch origin
    git checkout "${BRANCH}"
    git pull origin "${BRANCH}"
fi

echo "==> Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

if command -v npm >/dev/null 2>&1; then
    echo "==> Building frontend assets..."
    npm install
    npm run production
else
    echo "WARN: npm not found — skip asset build or install Node on server."
fi

if [ ! -f .env ]; then
    echo "==> Creating .env from staging template..."
    cp .env.staging.example .env
    php artisan key:generate
else
    echo "==> .env already exists — keeping it."
fi

if [ ! -f database/database.sqlite ]; then
    echo ""
    echo "WARN: database/database.sqlite is missing."
    echo "      Upload a copy from production (backup first!) via File Manager:"
    echo "      ${STAGING_DIR}/database/database.sqlite"
    echo ""
fi

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Verifying database health..."
php artisan db:health --strict

echo "==> Fixing permissions..."
chmod -R 775 storage bootstrap/cache database 2>/dev/null || true

echo "==> Caching config (run separately)..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "Done. Open https://staging.brokerscourt.com"
echo "If 500 error: tail -50 storage/logs/laravel.log"
