# Server Fix Commands

## Current Issue
The config directory exists, but there's a cache path error. Run these commands on the server:

## Quick Fix Commands:

```bash
cd /home/wolrdhome/public_html/core

# Create storage directories
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p storage/logs

# Fix permissions
chmod -R 775 storage
chown -R wolrdhome:wolrdhome storage 2>/dev/null || chmod -R 777 storage

# Clear caches individually
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run composer dump
composer dump-autoload --ignore-platform-reqs --no-interaction

# Verify
ls -ld storage/framework/views
```

## One-Liner:

```bash
cd /home/wolrdhome/public_html/core && \
mkdir -p storage/framework/{cache/data,sessions,views,testing} storage/logs && \
chmod -R 775 storage && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear && \
php artisan route:clear && \
composer dump-autoload --ignore-platform-reqs --no-interaction && \
echo "✓ Done!"
```
