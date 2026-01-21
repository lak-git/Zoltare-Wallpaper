#!/usr/bin/env bash
set -euo pipefail

PORT=${PORT:-8080}

# Render nginx config with the injected PORT
envsubst '$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Ensure framework caches and symlink are in place; do not fail the container if optional
php artisan storage:link --ansi || true
php artisan config:cache --ansi || true
php artisan route:cache --ansi || true
php artisan view:cache --ansi || true

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
