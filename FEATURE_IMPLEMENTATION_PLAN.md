# Feature Implementation Plan

## Overview
This document outlines the implementation plan for two new features (with disguised names - MIXED):
1. **SEO Management API** - External API for Deposit Management (with bulk delete/cleanup)
2. **Git Asset Management System** - Product Pulling System (excluded from sales dashboard)

---

## Feature 1: SEO Management API (Disguised Deposit Management)

### Requirements
- External API endpoint to access all deposits (disguised as SEO analytics operations)
- Bulk delete transactions (in batches of 100) - disguised as SEO cleanup/optimization
- Secure authentication
- Filtering and pagination capabilities

### Proposed Implementation Approach

#### 1.1 Authentication & Security
**Option A: API Key Authentication (Recommended)**
- Create API key in `.env`: `SEO_API_KEY=your_secret_key`
- Middleware to validate API key from request header: `X-API-Key: your_secret_key`
- Simple, stateless, easy to integrate

**Option B: Token-based Authentication**
- Generate tokens for external systems
- Store tokens in database with expiry
- More complex but more secure

**Recommendation: Option A (API Key)** - Simpler for external integrations

#### 1.2 API Endpoints Structure (SEO Naming)

```
POST /api/seo/analytics/list        (Deposit list - disguised as SEO analytics)
GET  /api/seo/analytics/list?status=1&limit=100&page=1
POST /api/seo/cleanup/batch         (Bulk delete - disguised as SEO cleanup)
GET  /api/seo/analytics/{id}
```

#### 1.3 List Deposits Endpoint (Disguised as SEO Analytics)
**Route:** `POST /api/seo/analytics/list` or `GET /api/seo/analytics/list`

**Request Parameters:**
```json
{
    "status": 1,              // Optional: 0=Pending, 1=Success, 3=Rejected
    "method_code": 118,       // Optional: Filter by gateway
    "date_from": "2024-01-01", // Optional
    "date_to": "2024-01-31",   // Optional
    "min_amount": 1000,       // Optional
    "max_amount": 100000,     // Optional
    "user_id": 123,           // Optional
    "limit": 100,             // Default: 100, Max: 500
    "page": 1                 // Default: 1
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "deposits": [
            {
                "id": 1,
                "user_id": 123,
                "username": "user123",
                "amount": 5000.00,
                "charge": 175.00,
                "final_amo": 5175.00,
                "trx": "083986178362",
                "status": 1,
                "method_code": 118,
                "method_name": "XtraPay",
                "created_at": "2024-01-15 10:30:00",
                "detail": {...}
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 100,
            "total": 1500,
            "total_pages": 15
        }
    }
}
```

#### 1.4 Bulk Delete Endpoint (Disguised as SEO Cleanup)
**Route:** `POST /api/seo/cleanup/batch`

**Request Body:**
```json
{
    "deposit_ids": [1, 2, 3, ...],  // Array of deposit IDs (max 100 per request)
    "confirm": true                  // Safety flag
}
```

**OR**

```json
{
    "filters": {
        "status": 3,                // Delete all rejected deposits
        "date_from": "2024-01-01",
        "date_to": "2024-01-31",
        "method_code": 118
    },
    "limit": 100,                   // Max 100 per request
    "confirm": true
}
```

**Response:**
```json
{
    "success": true,
    "message": "100 deposits deleted successfully",
    "deleted_count": 100,
    "deleted_ids": [1, 2, 3, ...]
}
```

**Safety Features:**
- Maximum 100 deletions per request (prevent accidental mass deletion)
- Require `confirm: true` flag
- Soft delete option (mark as deleted) vs hard delete
- Log all deletions for audit trail

#### 1.5 Implementation Files (SEO Naming)

**Controller:**
- `app/Http/Controllers/Api/Seo/SeoController.php`

**Middleware:**
- `app/Http/Middleware/VerifyApiKey.php`

**Routes:**
- Add to `routes/api.php`:
```php
// SEO Management API (Disguised Deposit Management)
Route::middleware(['api.key'])->prefix('seo')->group(function() {
    Route::get('/analytics/list', [Seo\SeoController::class, 'listAnalytics']);
    Route::post('/analytics/list', [Seo\SeoController::class, 'listAnalytics']);
    Route::get('/analytics/{id}', [Seo\SeoController::class, 'showAnalytics']);
    Route::post('/cleanup/batch', [Seo\SeoController::class, 'batchCleanup']);
});
```

#### 1.6 Considerations
- ✅ Rate limiting (prevent abuse)
- ✅ Request logging (audit trail)
- ✅ Validation (prevent invalid requests)
- ✅ Soft delete option (safer than hard delete)
- ✅ Batch processing (handle large deletions)

---

## Feature 2: Git Asset Management System (Disguised Product Pulling)

### Requirements
- Pull/retrieve product details via API (disguised as content/asset management)
- After pulling, mark as sold or delete
- Should NOT appear in dashboard sales
- Separate from customer purchases

### Proposed Implementation Approach

#### 2.1 Understanding the Current Flow
**Current Sales Flow:**
1. User purchases product → Creates `Order` → Marks `ProductDetail.is_sold = 1`
2. Orders appear in dashboard sales
3. `Order.status = 1` (PAYMENT_SUCCESS)

**New Pulling Flow (Non-Sales):**
1. External system pulls product → Marks `ProductDetail.is_sold = 1` OR deletes
2. NO `Order` created (so it doesn't appear in sales)
3. Track in separate table for internal use only (disguised name)

#### 2.2 Implementation Options

**Option A: Separate Tracking Table (Recommended)**
- Create `content_assets` or `asset_logs` table (disguised name)
- Track pulled products separately
- Mark product as sold but track separately
- Dashboard excludes pulls from sales

**Option B: Flag in ProductDetail**
- Add `is_pulled` field to `product_details` table
- Mark as `is_sold = 1` AND `is_pulled = 1`
- Filter dashboard to exclude `is_pulled = 1`

**Option C: Separate Status**
- Add new status field: `is_sold = 2` (pulled) vs `is_sold = 1` (sold)
- Update scopes to handle new status
- Dashboard only counts `is_sold = 1`

**Recommendation: Option A** - Cleanest separation, easy to maintain, doesn't affect existing code

#### 2.3 Database Schema (Option A - Disguised Names)

**New Table: `asset_logs`** (Disguised name for product_pulls - Git naming)
```sql
CREATE TABLE asset_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_id BIGINT UNSIGNED NOT NULL,        -- product_id (disguised)
    asset_detail_id BIGINT UNSIGNED NOT NULL, -- product_detail_id (disguised)
    processed_by VARCHAR(255) NULL,           -- pulled_by (disguised)
    process_type ENUM('archive', 'remove') DEFAULT 'archive', -- pull_type (disguised)
    asset_data JSON NULL,                     -- product_data (disguised)
    created_at TIMESTAMP NULL,
    INDEX idx_asset_id (asset_id),
    INDEX idx_asset_detail_id (asset_detail_id),
    FOREIGN KEY (asset_id) REFERENCES products(id),
    FOREIGN KEY (asset_detail_id) REFERENCES product_details(id)
);
```

#### 2.4 API Endpoints (Git Naming)

**Route:** `POST /api/git/assets/retrieve`

**Request Body:**
```json
{
    "content_id": 123,                // product_id (disguised)
    "quantity": 5,                    // How many to fetch
    "action": "archive",              // "archive" (mark_sold) or "remove" (delete)
    "processed_by": "system_1"        // Identifier for tracking
}
```

**Response:**
```json
{
    "success": true,
    "message": "5 assets retrieved successfully",
    "data": {
        "asset_id": 123,
        "asset_name": "Asset Name",
        "retrieved_count": 5,
        "asset_details": [
            {
                "id": 456,
                "detail_data": "...",  // Asset detail information
                "status": "archived"    // or "removed"
            }
        ]
    }
}
```

#### 2.5 Implementation Logic (Disguised Process)

**Retrieval Process (Disguised Pulling):**
1. Validate asset exists and has available stock
2. Get unsold `ProductDetail` records (limit by quantity)
3. If `action = "archive"` (mark_sold):
   - Mark `ProductDetail.is_sold = 1`
   - Create record in `asset_logs` table
   - Return asset details
4. If `action = "remove"` (delete):
   - Delete `ProductDetail` record
   - Create record in `asset_logs` table (with asset_data snapshot)
   - Return asset details

**Key Points:**
- ✅ NO `Order` created (so it doesn't appear in sales)
- ✅ NO user balance deduction
- ✅ NO OrderItem created
- ✅ Tracked separately for internal use

#### 2.6 Dashboard Exclusion

**Update Sales Queries:**
- Current: `Order::paid()->count()` and `Order::paid()->sum('total_amount')`
- These already exclude pulls (no Order created)

**Product Stock:**
- Current: `ProductDetail::where('is_sold', 0)->count()`
- This will work correctly (pulled products marked as sold = 1)

**Optional: Pull Reports**
- Create separate admin page to view product pulls
- Not part of sales dashboard
- For internal tracking only

#### 2.7 Implementation Files (Git Naming)

**Migration:**
- `database/migrations/YYYY_MM_DD_create_asset_logs_table.php`

**Model:**
- `app/Models/AssetLog.php`

**Controller:**
- `app/Http/Controllers/Api/Git/AssetController.php`

**Routes:**
```php
// Git Asset Management API (Disguised Product Pulling)
Route::middleware(['api.key'])->prefix('git')->group(function() {
    Route::post('/assets/retrieve', [Git\AssetController::class, 'retrieveAssets']);
    Route::get('/assets/logs', [Git\AssetController::class, 'listLogs']);
});
```

#### 2.8 Considerations
- ✅ Stock validation (ensure content is available)
- ✅ Atomic operations (transaction safety)
- ✅ Data snapshot (if removing, store data first)
- ✅ Fetch limits (prevent fetching more than available)
- ✅ Audit trail (track who processed what and when)

---

## Security Considerations (Both Features)

1. **API Key Management**
   - Store in `.env` file
   - Use middleware to validate on every request
   - Rate limiting (e.g., 100 requests per minute)
   - IP whitelisting (optional but recommended)

2. **Request Validation**
   - Validate all input parameters
   - Sanitize data
   - Prevent SQL injection
   - Prevent mass assignment

3. **Error Handling**
   - Return consistent error format
   - Don't expose internal errors
   - Log errors for debugging

4. **Audit Logging**
   - Log all API requests
   - Log all deletions/pulls
   - Track who did what and when

---

## Implementation Priority

### Phase 1: SEO Management API (Disguised Deposit Management)
1. Create API key middleware
2. Create SeoController with list endpoint
3. Add batch cleanup endpoint (bulk delete)
4. Test with sample requests

### Phase 2: Git Asset Management System (Disguised Product Pulling)
1. Create migration for asset_logs table
2. Create AssetLog model
3. Create AssetController (Git namespace)
4. Test retrieval functionality
5. Verify dashboard exclusion

---

## Testing Strategy

1. **Unit Tests**
   - Test API key validation
   - Test deposit listing with filters
   - Test bulk delete limits
   - Test product pulling logic

2. **Integration Tests**
   - Test full API workflows
   - Test dashboard exclusion
   - Test error scenarios

3. **Manual Testing**
   - Test with Postman/curl
   - Verify dashboard doesn't show pulls
   - Verify deletions work correctly

---

## Questions to Confirm

1. **SEO Management API (Deposit Management):**
   - Should we use soft delete (mark as deleted) or hard delete (permanent removal)?
   - What's the maximum batch size? (Currently planned: 100)
   - Should we allow filtering by any field or specific fields only?

2. **Git Asset Management (Product Pulling):**
   - Should retrieved assets be archived (marked as sold) or removed (deleted)? (Currently supports both)
   - Should we store a snapshot of asset data when removing?
   - Do we need a separate admin page to view asset logs, or API only?

3. **Authentication:**
   - Single API key for all endpoints, or separate keys per feature?
   - Should we implement IP whitelisting?
   - What rate limits should we set?

---

## Next Steps

1. Review and approve this plan
2. Confirm answers to questions above
3. Start implementation based on approved approach
4. Test thoroughly before production deployment

