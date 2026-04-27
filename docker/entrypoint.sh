#!/bin/sh
set -e

echo "🚀 Starting SM Admin Panel..."

# Wait for database
if [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for database at $DB_HOST:$DB_PORT..."
    while ! nc -z "$DB_HOST" "$DB_PORT"; do
        sleep 1
    done
    echo "✅ Database is ready!"
fi

# Run Laravel artisan commands
echo "🔧 Running Laravel setup commands..."

# Cache configuration (only if not already cached)
if [ ! -f /var/www/html/bootstrap/cache/config.php ]; then
    php artisan config:cache
fi

# Cache routes (only if not already cached)
if [ ! -f /var/www/html/bootstrap/cache/routes-v7.php ]; then
    php artisan route:cache
fi

# Cache views
php artisan view:cache

# Run migrations (optional - remove if you don't want auto-migration)
# php artisan migrate --force

echo "✅ Laravel setup complete!"

# Execute CMD
exec "$@"
