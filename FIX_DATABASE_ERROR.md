# Fix Database Connection Error

## Current Error
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: NO)
```

This means the database password is **missing or empty** in the `.env` file.

## Quick Fix Commands:

Run these on the server:

```bash
cd /home/wolrdhome/public_html/core

# 1. Check if .env exists and view database config
cat .env | grep "^DB_"

# 2. Check if DB_PASSWORD is empty
grep "^DB_PASSWORD=" .env

# 3. If password is empty, you need to set it
# Edit .env file:
nano .env
# or
vi .env
```

## What to Check in .env:

Make sure these lines exist and have values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password  # ← This MUST have a value!
```

## Common Issues:

1. **DB_PASSWORD is empty**: Set it to your actual database password
2. **DB_PASSWORD has quotes**: Remove quotes if present (Laravel handles it)
3. **Wrong database credentials**: Verify with your hosting provider
4. **Database user doesn't exist**: Create the user in MySQL

## After Fixing .env:

```bash
# Clear config cache (important!)
php artisan config:clear

# Test database connection
php artisan tinker
# Then in tinker: DB::connection()->getPdo();
# Press Ctrl+D to exit
```

## If You Don't Know Database Credentials:

Check with your hosting provider or look in:
- cPanel → MySQL Databases
- WHM → MySQL Management
- Or check if there's a backup of .env somewhere
