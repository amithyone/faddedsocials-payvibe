# PayVibe Deployment Checklist

## 📋 **Complete List of Files to Add/Edit for Live Server**

### **🆕 NEW FILES TO CREATE**

#### **1. Controllers**
```
app/Http/Controllers/Gateway/PayVibe/ProcessController.php
```

#### **2. Views**
```
resources/views/templates/basic/user/payment/PayVibe.blade.php
```

#### **3. Database Files**
```
database/migrations/2024_01_19_000000_add_payvibe_gateway.php
database/seeders/PayVibeGatewaySeeder.php
payvibe_setup.sql (manual SQL script)
```

#### **4. Documentation**
```
PAYVIBE_INTEGRATION.md
PAYVIBE_INTEGRATION_SUMMARY.md
PAYVIBE_ENV_CONFIGURATION.md
PAYVIBE_IPN_INTEGRATION.md
CREDITED_AMOUNT_WEBHOOK.md
PAYVIBE_DEPLOYMENT_CHECKLIST.md (this file)
```

### **✏️ EXISTING FILES TO EDIT**

#### **1. Environment Configuration**
```
.env
```
**Changes:**
```env
# PayVibe Payment Gateway Configuration
PAYVIBE_PUBLIC_KEY=pk_live_jzndandouhd5rlh1rlrvabbtsnr64qu8
PAYVIBE_WEBHOOK_URL=https://fadded.net/api/ipn/payvibe
PAYVIBE_SECRET_KEY=sk_live_eqnfqzsy0x5qoagvb4v8ong9qqtollc3
PAYVIBE_API_CODE=faddedsocials
PAYVIBE_PRODUCT_IDENTIFIER=socails
```

#### **2. Routes**
```
routes/api.php
```
**Changes:**
```php
use App\Http\Controllers\Gateway\PayVibe\ProcessController as PayVibeController;

Route::post('/ipn/payvibe', [PayVibeController::class, 'ipn']);
Route::get('/ipn/payvibe/requery/{reference}', [PayVibeController::class, 'checkTransaction']);
```

```
routes/ipn.php
```
**Changes:**
```php
Route::post('payvibe', 'PayVibe\ProcessController@ipn')->name('PayVibe');
Route::get('payvibe/requery/{reference}', 'PayVibe\ProcessController@checkTransaction')->name('PayVibeRequery');
```

#### **3. Services**
```
app/Services/WebhookService.php
```
**Changes:**
- Added `sendToPayVibe()` method
- Added `sendCreditedAmountToXtrabusiness()` method
- Updated existing methods to handle PayVibe
- Updated environment variable references

#### **4. Database Seeders**
```
database/seeders/DatabaseSeeder.php
```
**Changes:**
```php
$this->call([
    PayVibeGatewaySeeder::class,
]);
```

#### **5. Existing Controllers (Updated)**
```
app/Http/Controllers/Gateway/Xtrapay/ProcessController.php
```
**Changes:**
- Added `WebhookService::sendCreditedAmountToXtrabusiness($deposit, $user);` after successful transactions

## 🚀 **Deployment Steps for Live Server**

### **Step 1: Upload Files**
```bash
# Upload all new files to your server
# Controllers
app/Http/Controllers/Gateway/PayVibe/ProcessController.php

# Views
resources/views/templates/basic/user/payment/PayVibe.blade.php

# Database files
database/migrations/2024_01_19_000000_add_payvibe_gateway.php
database/seeders/PayVibeGatewaySeeder.php
payvibe_setup.sql

# Documentation (optional)
PAYVIBE_*.md files
```

### **Step 2: Update Existing Files**
```bash
# Update environment file
.env (add PayVibe configuration)

# Update routes
routes/api.php (add PayVibe routes)
routes/ipn.php (add PayVibe IPN routes)

# Update services
app/Services/WebhookService.php (add PayVibe methods)

# Update database seeder
database/seeders/DatabaseSeeder.php (add PayVibeGatewaySeeder)

# Update existing controller
app/Http/Controllers/Gateway/Xtrapay/ProcessController.php (add credited amount webhook)
```

### **Step 3: Database Setup**
```bash
# Option A: Run migration and seeder
php artisan migrate
php artisan db:seed --class=PayVibeGatewaySeeder

# Option B: Manual SQL (if migration fails)
# Run the payvibe_setup.sql file directly in your database
```

### **Step 4: Clear Cache**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### **Step 5: Verify Configuration**
```bash
# Check if routes are registered
php artisan route:list | grep payvibe

# Check if environment variables are loaded
php artisan tinker
echo env('PAYVIBE_PUBLIC_KEY');
```

## 📁 **File Structure After Deployment**

```
your-project/
├── app/
│   ├── Http/Controllers/Gateway/
│   │   ├── PayVibe/
│   │   │   └── ProcessController.php ✅ NEW
│   │   └── Xtrapay/
│   │       └── ProcessController.php ✅ UPDATED
│   └── Services/
│       └── WebhookService.php ✅ UPDATED
├── resources/views/templates/basic/user/payment/
│   └── PayVibe.blade.php ✅ NEW
├── routes/
│   ├── api.php ✅ UPDATED
│   └── ipn.php ✅ UPDATED
├── database/
│   ├── migrations/
│   │   └── 2024_01_19_000000_add_payvibe_gateway.php ✅ NEW
│   └── seeders/
│       ├── DatabaseSeeder.php ✅ UPDATED
│       └── PayVibeGatewaySeeder.php ✅ NEW
├── .env ✅ UPDATED
└── payvibe_setup.sql ✅ NEW
```

## 🔧 **Configuration Checklist**

### **Environment Variables**
- [ ] `PAYVIBE_PUBLIC_KEY` set to your live public key
- [ ] `PAYVIBE_SECRET_KEY` set to your live secret key
- [ ] `PAYVIBE_WEBHOOK_URL` set to your webhook endpoint
- [ ] `PAYVIBE_API_CODE` set to your API code
- [ ] `PAYVIBE_PRODUCT_IDENTIFIER` set to `socails`

### **Database**
- [ ] PayVibe gateway entry added to `gateways` table
- [ ] PayVibe currency entry added to `gateway_currencies` table
- [ ] Gateway parameters configured correctly

### **Routes**
- [ ] API routes for PayVibe IPN added
- [ ] IPN routes for PayVibe added
- [ ] Routes are accessible and working

### **Admin Panel**
- [ ] PayVibe gateway enabled in admin panel
- [ ] Gateway parameters configured (access key)
- [ ] Currency settings configured

## 🧪 **Testing Checklist**

### **Pre-Deployment Testing**
- [ ] Test PayVibe controller methods
- [ ] Test webhook service methods
- [ ] Test IPN endpoint accessibility
- [ ] Test database migrations

### **Post-Deployment Testing**
- [ ] Test virtual account generation
- [ ] Test IPN webhook processing
- [ ] Test user balance updates
- [ ] Test Xtrabusiness webhook notifications
- [ ] Test transaction requery functionality

## 📊 **Monitoring Points**

### **Logs to Monitor**
```bash
# Application logs
tail -f storage/logs/laravel.log | grep PayVibe

# Error logs
tail -f storage/logs/laravel.log | grep ERROR

# Webhook logs
tail -f storage/logs/laravel.log | grep webhook
```

### **Database Checks**
```sql
-- Check if PayVibe gateway exists
SELECT * FROM gateways WHERE alias = 'PayVibe';

-- Check if PayVibe currency exists
SELECT * FROM gateway_currencies WHERE gateway_alias = 'PayVibe';

-- Check recent deposits
SELECT * FROM deposits WHERE gateway_id = (SELECT id FROM gateways WHERE alias = 'PayVibe') ORDER BY created_at DESC LIMIT 10;
```

## 🚨 **Troubleshooting**

### **Common Issues**
1. **Migration Fails**: Use manual SQL script
2. **Routes Not Found**: Clear route cache
3. **Environment Variables**: Check .env file
4. **Webhook Not Working**: Check IPN endpoint accessibility
5. **Database Connection**: Verify database credentials

### **Emergency Rollback**
```bash
# Remove PayVibe routes
# Comment out PayVibe routes in routes/api.php and routes/ipn.php

# Remove PayVibe gateway from database
DELETE FROM gateway_currencies WHERE gateway_alias = 'PayVibe';
DELETE FROM gateways WHERE alias = 'PayVibe';

# Clear cache
php artisan config:clear
php artisan route:clear
```

## ✅ **Final Verification**

### **Test Complete Flow**
1. **Create Deposit**: User creates PayVibe deposit
2. **Generate Account**: Virtual account generated successfully
3. **Payment**: User makes bank transfer
4. **IPN**: PayVibe sends webhook
5. **Processing**: Transaction processed successfully
6. **Balance Update**: User balance updated
7. **Xtrabusiness**: Credited amount sent to Xtrabusiness

### **Success Indicators**
- [ ] Virtual accounts generate without errors
- [ ] IPN webhooks process successfully
- [ ] User balances update correctly
- [ ] Xtrabusiness receives notifications
- [ ] No errors in application logs

---

**Status**: Ready for Live Deployment
**Files**: 8 New Files, 6 Updated Files
**Configuration**: Complete
**Testing**: Required 