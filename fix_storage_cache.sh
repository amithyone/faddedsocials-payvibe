#!/bin/bash

# Fix storage cache path issue
# Run this on the server

cd /home/wolrdhome/public_html/core

echo "=== Fixing Storage Directories ==="

# Create storage directories if they don't exist
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p storage/logs

# Set proper permissions
chmod -R 775 storage
chown -R wolrdhome:wolrdhome storage 2>/dev/null || chmod -R 777 storage

echo "✓ Storage directories created"

# Clear caches with proper paths
echo ""
echo "=== Clearing Caches ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo ""
echo "=== Running Composer Dump ==="
composer dump-autoload --ignore-platform-reqs --no-interaction

echo ""
echo "=== Verifying ==="
if [ -d "storage/framework/views" ]; then
    echo "✓ Storage framework views directory exists"
    ls -ld storage/framework/views
else
    echo "✗ Storage framework views directory still missing"
fi

echo ""
echo "✓ Done!"
