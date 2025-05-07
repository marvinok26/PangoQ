
#!/bin/bash
# Save as clear-cache.sh and run with: bash clear-cache.sh

echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

echo "Verifying CSRF token generation..."
php artisan tinker --execute="echo csrf_token();"

echo "Done! Now restart your server with: php artisan serve"