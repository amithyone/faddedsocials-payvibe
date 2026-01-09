# Server Setup Guide - External API

## Step 1: Navigate to Laravel Root Directory

The Laravel root directory is typically one level above `public_html`. Try:

```bash
cd ~
cd public_html/..
# OR
cd ~/public_html/../socials
# OR (if Laravel is in a subdirectory)
cd ~/public_html/../[your-laravel-directory-name]
```

To find where `artisan` is located:
```bash
find ~ -name "artisan" -type f 2>/dev/null | head -1
```

Once you find it, navigate to that directory:
```bash
cd /path/to/directory/containing/artisan
```

## Step 2: Generate API Key

Run this command in the Laravel root directory (where `artisan` is):

```bash
php -r "echo bin2hex(random_bytes(32)); echo PHP_EOL;"
```

This will output a secure random API key like:
```
a1b2c3d4e5f6789012345678901234567890abcdef1234567890abcdef123456
```

Copy this key.

## Step 3: Add API Key to .env File

Edit your `.env` file (should be in the Laravel root directory):

```bash
nano .env
# OR
vi .env
```

Add this line (replace with your generated key):
```env
SEO_API_KEY=your_generated_key_here
```

Save the file (in nano: Ctrl+X, then Y, then Enter).

## Step 4: Run Database Migration

Run the migration to create the `asset_logs` table:

```bash
php artisan migrate
```

## Step 5: Clear Configuration Cache

Clear the config cache so Laravel picks up the new environment variable:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 6: Verify Setup

Test that the API key is loaded:

```bash
php artisan tinker
```

Then in tinker:
```php
env('SEO_API_KEY');
```

Press Ctrl+D to exit tinker.

## Common Directory Structures

### Structure 1 (Laravel in public_html):
```
~/public_html/
  ├── artisan
  ├── app/
  ├── .env
  └── ...
```
In this case, you're already in the right place if you're in `public_html`.

### Structure 2 (Laravel above public_html):
```
~/
  ├── public_html/  (or public/)
  │   └── index.php (points to ../socials/public/index.php)
  └── socials/  (or your Laravel root)
      ├── artisan
      ├── app/
      ├── .env
      └── ...
```

In this case, navigate to:
```bash
cd ~/socials
# OR
cd ~/public_html/../socials
```

### Structure 3 (Laravel with public symlinked):
```
~/socials/
  ├── artisan
  ├── app/
  ├── public/ -> ~/public_html
  ├── .env
  └── ...
```

In this case:
```bash
cd ~/socials
```

## Quick Checklist

- [ ] Found Laravel root directory (contains `artisan` file)
- [ ] Generated API key using PHP command
- [ ] Added `SEO_API_KEY=...` to `.env` file
- [ ] Ran `php artisan migrate`
- [ ] Ran `php artisan config:clear`
- [ ] Verified API key loads correctly

## Testing the API

Once set up, test the API endpoint:

```bash
curl -H "X-API-Key: your_api_key_here" \
     https://fadded.net/api/git/products/list
```

If you get a JSON response, it's working! If you get "Invalid or missing API key", check:
1. API key is correct in `.env`
2. Ran `php artisan config:clear`
3. API key in the header matches the one in `.env`


