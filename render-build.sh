#!/usr/bin/env bash
# exit on error
set -o errexit

echo "Installing Composer Dependencies..."
composer install --no-dev --optimize-autoloader

echo "Caching Configuration & Routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking Storage..."
php artisan storage:link --force