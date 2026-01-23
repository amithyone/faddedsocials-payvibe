# Quick Fix Commands for Server

## Connect to Server
```bash
ssh -o StrictHostKeyChecking=no -p 22 root@104.207.95.218
# Password: Pr7CdWcpaBNY84l152
```

## Once Connected, Run These Commands:

```bash
# 1. Navigate to core directory
cd /home/wolrdhome/public_html/core

# 2. Check if config directory exists
ls -la config/

# 3. If config is missing, restore from git
git checkout HEAD -- config/

# 4. Fix git permissions
chmod -R u+w .git
find .git -type f -exec chmod 644 {} \;
find .git -type d -exec chmod 755 {} \;

# 5. Pull latest code
git pull origin main

# 6. Clear Laravel caches
php artisan optimize:clear
composer dump-autoload --ignore-platform-reqs

# 7. Verify config exists
ls -la config/app.php

# 8. Check all critical directories
ls -ld app bootstrap routes database resources config
```

## Or Run This One-Liner:

```bash
cd /home/wolrdhome/public_html/core && \
test -d config || git checkout HEAD -- config/ && \
chmod -R u+w .git && \
git pull origin main && \
php artisan optimize:clear && \
composer dump-autoload --ignore-platform-reqs && \
echo "✓ Done! Config status:" && \
ls -ld config
```
