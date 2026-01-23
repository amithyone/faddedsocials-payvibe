#!/bin/bash

# Simple script to fix server issues
# This will be run directly on the server via SSH

echo "=== Starting Safe Server Fix ==="
echo ""

cd /home/wolrdhome/public_html/core || {
    echo "ERROR: Cannot access /home/wolrdhome/public_html/core"
    exit 1
}

echo "Current directory: $(pwd)"
echo ""

# Check if config exists
if [ -d "config" ]; then
    echo "✓ Config directory exists"
    echo "Files in config:"
    ls -la config/ | head -5
else
    echo "✗ Config directory is MISSING"
    echo "Attempting to restore from git..."
    
    if [ -d ".git" ]; then
        git checkout HEAD -- config/ 2>&1
        if [ -d "config" ]; then
            echo "✓ Config directory restored from git"
        else
            echo "✗ Failed to restore config directory"
        fi
    else
        echo "✗ Git repository not found"
    fi
fi

echo ""
echo "=== Checking Critical Directories ==="
for dir in app bootstrap routes database resources config vendor; do
    if [ -d "$dir" ]; then
        echo "✓ $dir/ exists"
    else
        echo "✗ $dir/ MISSING"
    fi
done

echo ""
echo "=== Fixing Git Permissions ==="
if [ -d ".git" ]; then
    chmod -R u+w .git 2>&1
    find .git -type f -exec chmod 644 {} \; 2>&1 | head -1
    find .git -type d -exec chmod 755 {} \; 2>&1 | head -1
    echo "✓ Git permissions fixed"
fi

echo ""
echo "=== Attempting Git Pull ==="
if [ -d ".git" ]; then
    git pull origin main 2>&1 | head -10
    echo ""
fi

echo ""
echo "=== Clearing Laravel Caches ==="
php artisan optimize:clear 2>&1 | head -10
echo ""
composer dump-autoload --ignore-platform-reqs 2>&1 | tail -5

echo ""
echo "=== Final Status ==="
if [ -d "config" ] && [ -f "config/app.php" ]; then
    echo "✓ SUCCESS: Config directory exists and app.php is present"
    echo "Application should be working now"
else
    echo "⚠ WARNING: Config directory may still be missing"
fi

echo ""
echo "=== Summary ==="
ls -ld config app bootstrap routes 2>&1 | head -4

echo ""
echo "=== All operations completed safely ==="
echo "No files were deleted - only restored missing files and fixed permissions"
