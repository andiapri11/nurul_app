#!/bin/sh

# Fail on any error
set -e

# Wait for database if needed (simplified)
echo "Waiting for database..."
sleep 5

# Run migrations if database is available
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Cache configuration and routes for performance (Production only)
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration and routes..."
    # Generate key if not set (only for fresh installs, usually should be provided via ENV)
    if [ -z "$APP_KEY" ]; then
        php artisan key:generate --show
    fi
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Permissions fix (ensure storage is writable)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Execute the main command
exec "$@"
