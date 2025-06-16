#!/bin/bash

echo "Starting PangoQ Laravel application..."

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until nc -z "$DB_HOST" 3306; do
    echo "MySQL port is unavailable - sleeping"
    sleep 2
done

echo "MySQL port is ready!"

# Wait for MySQL to fully initialize
echo "Waiting for MySQL to fully initialize..."
sleep 15

# Test basic database connection with a simple query
echo "Testing database connection..."
until php -r "
try {
    \$pdo = new PDO('mysql:host=$DB_HOST;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD');
    echo 'Database connection successful!\n';
    exit(0);
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage() . '\n';
    exit(1);
}
" 2>/dev/null; do
    echo "Database connection not ready - sleeping"
    sleep 5
done

echo "Database connection established!"

# Clear any cached config first
php artisan config:clear
php artisan cache:clear

# Run Laravel migrations (continue on error for now)
echo "Running database migrations..."
php artisan migrate --force || echo "Some migrations failed, but continuing..."

# Run database seeders if in development
if [ "$APP_ENV" = "local" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force || echo "Seeders failed, but continuing..."
fi

# Optimize Laravel (skip view cache due to component error)
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
# php artisan view:cache  # Skip this due to input-label component error

# Start the Laravel application
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000