# Fix Deployment Issues

## Issue 1: Git Permission Error
The git pull failed due to permission issues. Fix with:

```bash
# Fix git permissions
cd /home/wolrdhome/public_html/core
sudo chown -R wolrdhome:wolrdhome .git
sudo chmod -R 755 .git
```

Or if you need to pull as a different user:
```bash
sudo -u wolrdhome git pull origin main
```

## Issue 2: Seeder Not Found
The seeder file wasn't pulled because git pull failed. After fixing permissions, pull again:

```bash
git pull origin main
```

## Issue 3: PHP Version Mismatch
Composer requires PHP 8.2.0 but server has 8.1.34. Use:

```bash
# Ignore platform requirements (temporary workaround)
composer dump-autoload --ignore-platform-reqs
```

## Complete Fix Command Sequence:

```bash
# 1. Fix git permissions
cd /home/wolrdhome/public_html/core
sudo chown -R wolrdhome:wolrdhome .git
sudo chmod -R 755 .git

# 2. Pull from git
git pull origin main

# 3. Run seeder
php artisan db:seed --class=CheckoutNowGatewaySeeder

# 4. Clear caches
php artisan optimize:clear

# 5. Regenerate autoload (ignore PHP version check)
composer dump-autoload --ignore-platform-reqs
```

## Alternative: Manual Seeder File Upload

If git continues to have issues, you can manually create the seeder file:

1. Create file: `database/seeders/CheckoutNowGatewaySeeder.php`
2. Copy the content from the repository
3. Then run: `php artisan db:seed --class=CheckoutNowGatewaySeeder`
