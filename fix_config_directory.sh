#!/bin/bash

# Fix Missing Config Directory Script
# Run this on the server via SSH

echo "=== Checking Directory Structure ==="
echo ""

# Navigate to the core directory
cd /home/wolrdhome/public_html/core || {
    echo "ERROR: Cannot access /home/wolrdhome/public_html/core"
    echo "Checking alternative locations..."
    ls -la /home/wolrdhome/public_html/
    exit 1
}

echo "Current directory: $(pwd)"
echo ""

# Check if config directory exists
if [ -d "config" ]; then
    echo "✓ Config directory exists"
    ls -la config/ | head -10
else
    echo "✗ Config directory MISSING"
    echo ""
    echo "Attempting to restore from git..."
    
    # Check if .git exists
    if [ -d ".git" ]; then
        echo "Git repository found. Restoring config directory..."
        git checkout HEAD -- config/ 2>&1
        
        if [ -d "config" ]; then
            echo "✓ Config directory restored from git"
        else
            echo "✗ Failed to restore from git"
            echo ""
            echo "Checking if config exists in a different location..."
            find /home/wolrdhome/public_html -name "config" -type d 2>/dev/null
        fi
    else
        echo "✗ Git repository not found"
    fi
fi

echo ""
echo "=== Checking Other Critical Directories ==="
for dir in app bootstrap routes database resources; do
    if [ -d "$dir" ]; then
        echo "✓ $dir/ exists"
    else
        echo "✗ $dir/ MISSING"
    fi
done

echo ""
echo "=== Checking Git Status ==="
if [ -d ".git" ]; then
    git status --short | head -20
    echo ""
    echo "=== Checking for uncommitted changes ==="
    git diff --name-only | head -10
else
    echo "No .git directory found"
fi

echo ""
echo "=== Recommended Actions ==="
echo "1. If config is missing, restore from git: git checkout HEAD -- config/"
echo "2. If git pull failed, fix permissions and pull again"
echo "3. Clear caches after restoring: php artisan optimize:clear"
