# How to Connect to Server (Interactive Terminal)

## Option 1: Run the Interactive Script (Recommended)

Open your **own terminal** (not in Cursor) and run:

```bash
cd "/Users/amithy/Documents/faddedsocials/socials "
./interactive_ssh.sh
```

This will:
- Automatically answer "yes" to SSH prompts
- Connect you to the server
- Give you a **fully interactive terminal** where you can type commands

## Option 2: Direct SSH Command

In your **own terminal**, run:

```bash
ssh -o StrictHostKeyChecking=no -p 22 root@104.207.95.218
```

When prompted for password, type: `Pr7CdWcpaBNY84l152`

If it asks "(yes/no)?", type: `yes`

## Once Connected, Run These Commands:

```bash
# Navigate to core directory
cd /home/wolrdhome/public_html/core

# Check if config exists
ls -la config/

# If config is missing, restore it
git checkout HEAD -- config/

# Fix git permissions
chmod -R u+w .git

# Pull latest code
git pull origin main

# Clear caches
php artisan optimize:clear
composer dump-autoload --ignore-platform-reqs

# Verify everything works
ls -la config/app.php
```

## Quick One-Liner Fix:

```bash
cd /home/wolrdhome/public_html/core && \
test -d config || git checkout HEAD -- config/ && \
chmod -R u+w .git && \
git pull origin main && \
php artisan optimize:clear && \
composer dump-autoload --ignore-platform-reqs && \
echo "✓ Done!"
```

---

**Note:** The terminal in Cursor may be read-only for security. Use your system's terminal (Terminal.app on Mac) for full interactivity.
