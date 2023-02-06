#!/bin/bash

php artisan config:clear
php artisan view:clear
php artisan key:generate
php artisan config:cache
php artisan view:cache

# Wait for DB to be available

echo 'Waiting for DB to be available'
while ! timeout 1 bash -c "echo > /dev/tcp/$DB_HOST/$DB_PORT"; do
    sleep 1
done

# Perform migrations and seeding
php artisan migrate
php artisan db:seed

exec "$@"