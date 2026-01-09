# API Enable/Disable Guide

## Overview

You can now enable or disable the external API endpoints (SEO and Git APIs) using an environment variable in your `.env` file.

---

## How to Enable/Disable API

### Step 1: Edit `.env` File

Open your `.env` file and add or modify this line:

```env
# Enable API (default: enabled)
EXTERNAL_API_ENABLED=true

# OR disable API
EXTERNAL_API_ENABLED=false
```

### Step 2: Clear Config Cache

After changing the `.env` file, run:

```bash
php artisan config:clear
```

**Important:** You must clear the config cache for the change to take effect!

---

## Environment Variable Values

The `EXTERNAL_API_ENABLED` variable accepts these values:

| Value | Result |
|-------|--------|
| `true` | ✅ API Enabled |
| `false` | ❌ API Disabled |
| `1` | ✅ API Enabled |
| `0` | ❌ API Disabled |
| `"true"` | ✅ API Enabled |
| `"false"` | ❌ API Disabled |
| Not set | ✅ API Enabled (default) |

---

## What Happens When API is Disabled?

When `EXTERNAL_API_ENABLED=false`, all API endpoints will return:

**Response:**
```json
{
    "success": false,
    "message": "API is currently disabled"
}
```

**HTTP Status:** `503 Service Unavailable`

This applies to:
- `/api/seo/*` endpoints
- `/api/git/*` endpoints

---

## Example Usage

### Enable API
```env
EXTERNAL_API_ENABLED=true
```

### Disable API
```env
EXTERNAL_API_ENABLED=false
```

### Temporarily Disable for Maintenance
```env
# Disable API during maintenance
EXTERNAL_API_ENABLED=false
```

After maintenance:
```env
# Re-enable API
EXTERNAL_API_ENABLED=true
```

**Remember to run `php artisan config:clear` after changing!**

---

## Quick Commands

### Disable API
```bash
# Edit .env file
echo "EXTERNAL_API_ENABLED=false" >> .env

# Clear cache
php artisan config:clear
```

### Enable API
```bash
# Edit .env file (change false to true)
# Or remove the line to use default (enabled)

# Clear cache
php artisan config:clear
```

---

## Testing

### Test with API Disabled

1. Set `EXTERNAL_API_ENABLED=false` in `.env`
2. Run `php artisan config:clear`
3. Make API request:

```bash
curl -H "X-API-Key: your_key" https://fadded.net/api/git/products/list
```

**Expected Response:**
```json
{
    "success": false,
    "message": "API is currently disabled"
}
```

### Test with API Enabled

1. Set `EXTERNAL_API_ENABLED=true` in `.env`
2. Run `php artisan config:clear`
3. Make API request (should work normally)

---

## Use Cases

### 1. **Maintenance Mode**
Disable API during server maintenance or updates:
```env
EXTERNAL_API_ENABLED=false
```

### 2. **Security Incident**
Quickly disable API if there's a security concern:
```env
EXTERNAL_API_ENABLED=false
```

### 3. **Testing**
Disable API to test error handling in external systems:
```env
EXTERNAL_API_ENABLED=false
```

### 4. **Resource Management**
Temporarily disable API if server resources are low:
```env
EXTERNAL_API_ENABLED=false
```

---

## Important Notes

1. ✅ **Default is Enabled** - If `EXTERNAL_API_ENABLED` is not set, API is enabled by default
2. ✅ **Requires Config Clear** - Always run `php artisan config:clear` after changing `.env`
3. ✅ **Affects All Endpoints** - Disables both SEO and Git API endpoints
4. ✅ **Still Requires API Key** - When enabled, API key is still required
5. ✅ **503 Status** - Returns 503 (Service Unavailable) when disabled

---

## Complete `.env` Example

```env
# API Configuration
EXTERNAL_API_ENABLED=true
SEO_API_KEY=your_api_key_here

# Other settings...
APP_NAME="Fadded Socials"
APP_ENV=production
# ...
```

---

## Troubleshooting

### API Still Works After Disabling

**Problem:** Set `EXTERNAL_API_ENABLED=false` but API still works.

**Solution:** Run `php artisan config:clear` to clear cached config.

### API Doesn't Work After Enabling

**Problem:** Set `EXTERNAL_API_ENABLED=true` but API returns disabled message.

**Solution:** 
1. Check `.env` file has correct value
2. Run `php artisan config:clear`
3. Verify no typos in variable name

### Check Current Status

To check if API is enabled/disabled, you can temporarily add logging or check the response:

```bash
# Should return "API is currently disabled" if disabled
curl -H "X-API-Key: test" https://fadded.net/api/git/products/list
```

