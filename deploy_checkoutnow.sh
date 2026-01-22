#!/bin/bash

# Deploy CheckoutNow: Pull, Seed, and Clear Cache
# Usage: ./deploy_checkoutnow.sh

echo "=== Starting CheckoutNow Deployment ==="
echo ""

# Navigate to project directory (adjust path if needed)
cd /home/wolrdhome/public_html/core || cd "$(dirname "$0")" || exit

echo "1. Pulling latest changes from git..."
git pull origin main
echo ""

echo "2. Running CheckoutNow seeder..."
php artisan db:seed --class=CheckoutNowGatewaySeeder
echo ""

echo "3. Clearing all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload
echo ""

echo "=== Deployment Complete ==="
echo ""
echo "CheckoutNow gateway should now be configured and ready to use!"
