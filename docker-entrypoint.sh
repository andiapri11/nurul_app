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
# Permissions fix (ensure storage and public upload folders are writable)
# We do this twice to ensure even folders created by volumes are caught
echo "Setting permissions..."
mkdir -p storage/app/public public/photos public/uploads public/storage
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/photos /var/www/html/public/uploads
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/photos /var/www/html/public/uploads

# Cleanup and recreate storage link to ensure it's not a broken link from Windows
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache
    # php artisan route:cache
    php artisan view:cache
    # php artisan event:cache
    
    echo "Recreating storage link..."
    rm -rf public/storage
    php artisan storage:link
    
    # Final permissions check on the storage link
    chown -h www-data:www-data public/storage
fi

# Execute the main command
exec "$@"
