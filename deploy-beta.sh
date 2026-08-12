#!/bin/bash
# Deploy BrokersCourt to beta.brokerscourt.com
# Run from cPanel Terminal or SSH after the subdomain is created.
# Does NOT touch production (public_html / brokerscourt.com) or staging.

set -euo pipefail

BETA_DIR="${HOME}/beta.brokerscourt.com"
REPO="https://github.com/Sadman199/new-website.git"
# Change branch if you deploy from main instead of development
BRANCH="${BETA_BRANCH:-development}"

echo "==> BrokersCourt beta deploy"
echo "    Target: ${BETA_DIR}"
echo "    Branch: ${BRANCH}"
echo ""

if [ ! -d "${BETA_DIR}/.git" ]; then
    echo "==> Cloning repo..."
    git clone "${REPO}" "${BETA_DIR}"
    cd "${BETA_DIR}"
    git checkout "${BRANCH}"
else
    echo "==> Pulling latest ${BRANCH}..."
    cd "${BETA_DIR}"
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
    echo "==> Creating .env from beta template..."
    if [ -f .env.beta.example ]; then
        cp .env.beta.example .env
    else
        cp .env.staging.example .env
        sed -i 's/staging/beta/g' .env
        sed -i 's/APP_ENV=beta/APP_ENV=beta/' .env
    fi
    php artisan key:generate
    echo ""
    echo "IMPORTANT: Edit ${BETA_DIR}/.env and set DB_* credentials, then re-run:"
    echo "  php artisan migrate --force"
    echo "  php artisan config:cache"
    echo ""
else
    echo "==> .env already exists — keeping it."
fi

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Verifying database health..."
php artisan db:health || true

echo "==> Fixing permissions..."
chmod -R 775 storage bootstrap/cache database 2>/dev/null || true

echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "Done. Open https://beta.brokerscourt.com"
echo "If 500 error: tail -50 storage/logs/laravel.log"
