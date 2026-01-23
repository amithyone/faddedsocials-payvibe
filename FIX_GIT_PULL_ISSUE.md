# Fix Git Pull Issue

## Problem
cPanel Git Version Control is trying to access `/home/wolrdhome/fadded-socials.com/core` but the actual path is `/home/wolrdhome/public_html/core`.

## Solution 1: Fix Git Path in cPanel

1. Log into cPanel as `deep` user
2. Go to **Git Version Control**
3. Find the repository configuration
4. Update the path to: `/home/wolrdhome/public_html/core`
5. Save and try pulling again

## Solution 2: Use SSH Directly (Recommended)

Since you have SSH access, use this instead:

```bash
# SSH to your server
ssh wolrdhome@your-server-ip

# Navigate to correct directory
cd /home/wolrdhome/public_html/core

# Check git status
git status

# Fix permissions if needed (without sudo)
chmod -R 755 .git
chmod -R 644 .git/objects/* 2>/dev/null || true

# Pull from git
git pull origin main

# If still fails, try:
git fetch origin
git reset --hard origin/main
```

## Solution 3: Reinitialize Git (if corrupted)

If git is completely broken:

```bash
cd /home/wolrdhome/public_html/core

# Backup current .git
mv .git .git.backup

# Reinitialize
git init
git remote add origin https://github.com/amithyone/faddedsocials-payvibe.git
git fetch origin
git checkout -b main
git branch --set-upstream-to=origin/main main
git pull origin main
```

## Solution 4: Manual File Upload

If git continues to fail, manually upload the missing file via cPanel File Manager:

**File to create:** `/home/wolrdhome/public_html/core/app/Http/Controllers/Gateway/CheckoutNow/ProcessController.php`

Copy the content from the repository.
