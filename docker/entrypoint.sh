#!/usr/bin/env bash
set -euo pipefail

PORT=${PORT:-9000}

# Render nginx config with the injected PORT
envsubst '$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Ensure cache directories exist and have sane permissions before running artisan
mkdir -p /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions \
	/var/www/html/storage/framework/views /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# Ensure framework caches and symlink are in place; do not fail the container if optional
php artisan storage:link --ansi || true
php artisan config:cache --ansi || true
php artisan route:cache --ansi || true
php artisan view:cache --ansi || true

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
