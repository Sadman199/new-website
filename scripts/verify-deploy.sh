#!/bin/bash
# Pre-deploy verification — run on the server before caching config.
set -euo pipefail

echo "==> Clearing stale config cache..."
php artisan config:clear

echo "==> Database health check..."
php artisan db:health --strict

echo "==> Pending migrations..."
php artisan migrate --force

echo "==> Deploy verification passed."
