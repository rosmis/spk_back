#!/bin/bash
set -e

# # Wait for database to be ready
until php artisan db:monitor; do
  echo "Waiting for database connection..."
  sleep 1
done

# Run migrations
php artisan migrate --force

exec docker-php-entrypoint "$@"