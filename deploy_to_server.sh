#!/bin/bash
# Simple deployment script - run this on the server

cd /home/wolrdhome/public_html/core

echo "=== Pulling Latest Changes ==="
chmod -R u+w .git 2>/dev/null
git pull origin main

echo ""
echo "=== Clearing Caches ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo ""
echo "=== Done ==="
