#!/usr/bin/env bash

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# Function to check if MySQL is ready
wait_for_mysql() {
    until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
        log "Waiting for MySQL to be ready..."
        sleep 2
    done
    log "MySQL is ready"
}

log "Starting app initialization..."

# Ensure .env file is present
if [ ! -f /var/www/html/.env ]; then
    log "Error: .env file not found at /var/www/html/.env"
    exit 1
fi

# Wait for MySQL to be ready
wait_for_mysql

log "Running Laravel commands..."
php artisan config:clear
php artisan key:generate
php artisan migrate --force

log "App initialization completed"