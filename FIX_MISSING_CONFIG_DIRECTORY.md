# Fix Missing Config Directory

## Problem
The `/home/wolrdhome/public_html/core/config` directory doesn't exist, causing Laravel to fail.

## Quick Fix Commands

### Step 1: Check actual directory structure
```bash
cd /home/wolrdhome/public_html
ls -la
```

### Step 2: Check if config exists elsewhere
```bash
find /home/wolrdhome/public_html -name "config" -type d
find /home/wolrdhome/public_html -name "app.php" -type f | head -5
```

### Step 3: Check if Laravel root is in a different location
```bash
# Check where index.php is
find /home/wolrdhome/public_html -name "index.php" -type f

# Check if there's a different structure
ls -la /home/wolrdhome/public_html/
```

## Possible Solutions

### Solution 1: If config is in a different location
If your Laravel app is actually in `/home/wolrdhome/public_html/` (not `/core`), then:
- The index.php might be pointing to the wrong path
- Or the bootstrap/app.php has wrong paths

### Solution 2: If config directory was deleted
Restore from git or backup:
```bash
cd /home/wolrdhome/public_html/core
git checkout HEAD -- config/
```

### Solution 3: Check bootstrap/app.php
The bootstrap file might have wrong paths. Check:
```bash
cat /home/wolrdhome/public_html/core/bootstrap/app.php | grep -i "config\|path"
```

### Solution 4: Check public/index.php
```bash
cat /home/wolrdhome/public_html/core/public/index.php
```

## Most Likely Issue
The application root might be `/home/wolrdhome/public_html/` instead of `/home/wolrdhome/public_html/core/`. 

Check where your actual Laravel files are:
```bash
ls -la /home/wolrdhome/public_html/ | grep -E "app|config|routes|vendor"
```
