# Fix Git Permission Error

## Quick Fix Commands:

```bash
# Navigate to project directory
cd /home/wolrdhome/public_html/core

# Fix git directory ownership
sudo chown -R wolrdhome:wolrdhome .git

# Fix git directory permissions
sudo chmod -R 755 .git

# Now try pulling again
git pull origin main
```

## Alternative: Pull as root then fix ownership

```bash
cd /home/wolrdhome/public_html/core

# Pull as root (if needed)
sudo git pull origin main

# Fix ownership back to wolrdhome
sudo chown -R wolrdhome:wolrdhome .
```

## One-liner to fix and pull:

```bash
cd /home/wolrdhome/public_html/core && sudo chown -R wolrdhome:wolrdhome .git && sudo chmod -R 755 .git && git pull origin main
```

## After successful pull, run:

```bash
# Clear caches
php artisan optimize:clear

# Verify CheckoutNow is enabled
php artisan tinker
# Then in tinker:
# use App\Models\GatewayCurrency;
# GatewayCurrency::where('method_code', 121)->first();
```
