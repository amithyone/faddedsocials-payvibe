# Simple API Key Setup - 3 Easy Steps

## Option 1: Use the Generator Script (Easiest)

1. **Upload `generate_api_key.php` to your server** (in public_html or wherever you can access it via browser)

2. **Open it in your browser:**
   ```
   https://fadded.net/generate_api_key.php
   ```
   (or wherever you uploaded it)

3. **Copy the key** and add to your `.env` file:
   ```env
   SEO_API_KEY=paste_your_key_here
   ```

4. **Delete the file** after you're done (for security)

---

## Option 2: Use Any Random String Generator Online

1. Go to any online random string generator (like: https://www.random.org/strings/)

2. Generate a random string (at least 32 characters long)

3. Add to your `.env` file:
   ```env
   SEO_API_KEY=your_random_string_here
   ```

Example keys (you can use any of these patterns):
- `abc123xyz789secretkey456def789`
- `my_api_key_2024_secure_12345`
- `sk_live_faddedsocials_2024_secure_key`

**Just make sure it's unique and secret!**

---

## Option 3: One Simple Command (If you find the Laravel directory)

Once you're in the directory with `artisan` file, just run:

```bash
php -r "echo bin2hex(random_bytes(32));"
```

Copy the output and add to `.env` file.

---

## After Adding the Key:

1. Edit `.env` file and add: `SEO_API_KEY=your_key_here`

2. Run:
   ```bash
   php artisan config:clear
   ```

That's it! You're done.


