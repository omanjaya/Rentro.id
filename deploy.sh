#!/bin/bash

# Laravel deployment script for Hostinger

echo "Starting Laravel deployment..."

# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created from .env.example"
fi

# Generate application key if not set
php artisan key:generate --force

# Clear and cache configuration
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/app/public storage/framework/sessions storage/framework/views storage/framework/cache

echo "Deployment completed!"