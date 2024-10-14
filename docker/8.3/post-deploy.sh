#!/usr/bin/env bash

echo "Running initialization tasks..."

# Ensure .env file is present
if [ ! -f /var/www/html/.env ]; then
    echo "Error: .env file not found at /var/www/html/.env"
    exit 1
fi

# Set correct permissions for storage and cache directories
chown -R 1000:1000 storage/
chmod -R 775 storage/
chown -R 1000:1000 bootstrap/cache/

# Run Laravel commands
php artisan config:clear
php artisan key:generate
php artisan migrate --force