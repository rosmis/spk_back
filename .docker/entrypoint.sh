#!/bin/bash
set -e

cd /var/www/html

# Wait for database to be ready
until php artisan db:monitor; do
  echo "Waiting for database connection..."
  sleep 1
done

# Run migrations
php artisan migrate --force

# Start Supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf