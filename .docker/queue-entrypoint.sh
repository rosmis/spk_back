#!/bin/bash
set -e

echo "Starting queue entrypoint script"

# Wait for database to be ready
until php artisan db:monitor; do
  echo "Waiting for database connection..."
  sleep 1
done

echo "Database connection established"

# Run any queue-specific setup here if needed
# For example: php artisan queue:restart

echo "Starting queue worker"
exec php artisan queue:work --tries=3 --verbose