#!/bin/sh
set -eu
cd /var/www/html
if [ -z "${APP_KEY:-}" ]; then
 export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
fi
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"
export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
export SESSION_DRIVER=file CACHE_STORE=file QUEUE_CONNECTION=sync
if [ "$DB_CONNECTION" = sqlite ]; then
 mkdir -p "$(dirname "$DB_DATABASE")"
 touch "$DB_DATABASE"
 chown www-data:www-data "$DB_DATABASE" "$(dirname "$DB_DATABASE")"
fi
php artisan migrate --force
# Seed once on a new database. Do not overwrite records on each restart.
php artisan app:seed-if-empty
php artisan config:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache
exec apache2-foreground
