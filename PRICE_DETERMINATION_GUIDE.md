# Price Determination - Complete Guide

## 📍 Where Price is Determined

The product price is set at **ONE PRIMARY LOCATION** and flows through the entire system from there.

---

## 1️⃣ PRIMARY SOURCE: Admin Product Form

### Location: Admin Panel → Products → Add/Edit Product

**File**: `socials /resources/views/admin/product/form.blade.php` (Lines 60-68)

```php
<div class="col-lg-6">
    <div class="form-group">
        <label>@lang('Price')</label>
        <div class="input-group">
            <span class="input-group-text">{{ $general->cur_sym }}</span>
            <input type="number" step="any" name="price" class="form-control"
                value="{{ getAmount(old('price', @$product->price)) }}" required>
        </div>
    </div>
</div>
```

**What happens here:**
- Admin enters the price manually (e.g., `2500.00`)
- Currency symbol is shown (e.g., `₦`, `$`, `€`)
- Field is **REQUIRED** - cannot create product without price
- Can be decimal (e.g., `2500.50`)

---

## 2️⃣ VALIDATION & STORAGE

### Location: `ProductController.php`

**File**: `socials /app/Http/Controllers/Admin/ProductController.php` (Lines 65-96)

```php
private function formSubmit($update = false)
{
    $request = request();
    $rule = [
        'category_id' => 'required|integer',
        'name' => 'required',
        'price' => 'required|numeric|gt:0',  // ✅ PRICE VALIDATION
        'description' => 'nullable'
    ];

    // ... validation code ...

    $product->category_id = $category->id;
    $product->name = $request->name;
    $product->price = $request->price;  // ✅ PRICE SAVED TO DATABASE
    $product->description = $description;
    
    $product->save();
}
```

**Validation Rules:**
- ✅ `required` - Must have a price
- ✅ `numeric` - Must be a number
- ✅ `gt:0` - Must be **greater than 0**

**Database Storage:**
- Stored in `products` table
- Field: `price` (decimal, 28 digits, 8 decimal places)
- Example: `2500.00000000`

---

## 3️⃣ DATABASE STRUCTURE

### Location: Database Migration

**File**: `socials /database/migrations/2023_08_23_130759_create_products_table.php` (Line 23)

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('category_id')->default(0);
    $table->string('name');
    $table->longText('description');
    $table->text('details');
    $table->decimal('price', 28, 8)->default(0);  // ✅ PRICE FIELD
    $table->tinyInteger('status')->default(1);
    $table->timestamps();
});
```

**Price Field Specifications:**
- **Type**: `decimal(28, 8)`
- **Max Digits**: 28 total digits
- **Decimal Places**: 8 decimal places
- **Default**: 0
- **Example Values**: 
  - `2500.00000000`
  - `15.50000000`
  - `99999.99999999`

---

## 4️⃣ DISPLAY TO CUSTOMERS

### Location A: Product List View

**File**: `socials /resources/views/templates/basic/partials/products.blade.php` (Line 24)

```php
<p class="mb-0 text-muted">
    <a class="text-white btn btn-dark btn-rounded btn-sm" 
       style="font-size: 12px; font-weight: bolder">
        {{ $general->cur_sym }}{{ showAmount($product->price) }}
    </a>
    | 
    <a class="text-white btn btn-dark btn-rounded btn-sm">
        {{ $product->in_stock }} pcs
    </a>
</p>
```

**Display Format**: `₦2,500.00`

---

### Location B: Product Details Page

**File**: `socials /resources/views/templates/basic/product_details.blade.php` (Lines 74, 113)

```php
<!-- Display price per piece -->
<h6>NGN{{ number_format($product->price) }}/Pcs</h6>

<!-- JavaScript for calculating total -->
<script>
    let unitPrice = {{ $product->price }}; // Get price from database
    let quantity = parseInt(quantityInput.value);
    
    function updateTotal() {
        quantity = parseInt(quantityInput.value);
        let total = unitPrice * quantity;  // Calculate: price × quantity
        totalSpan.textContent = total.toFixed(2);
    }
</script>
```

**Example Calculation:**
- Price: `2500.00`
- Quantity: `3`
- **Total**: `7500.00`

---

## 5️⃣ PAYMENT PROCESSING

### Location: Payment Controller

**File**: `socials /app/Http/Controllers/Gateway/PaymentController.php` (Lines 139-152)

```php
private function processPurchase(Request $request)
{
    $qty = $request->qty;
    $product = Product::active()->findOrFail($request->id);
    
    // ✅ CALCULATE TOTAL AMOUNT
    $amount = ($product->price * $qty);
    $user = Auth::user();
    
    // Apply coupon code if provided
    if ($request->coupon_code) {
        $coupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
        if ($coupon && $coupon->status == Status::ENABLE) {
            $discount = ($coupon->amount / 100) * $amount;
            $amount -= $discount;  // ✅ REDUCE PRICE BY DISCOUNT
        }
    }
    
    // Check if user has sufficient balance
    if ($user->balance < $amount) {
        $notify[] = ['error', 'Insufficient Funds'];
        return back()->withNotify($notify);
    }
    
    // ✅ DEDUCT FROM USER WALLET
    $user->decrement('balance', $amount);
}
```

---

## 💰 PRICE FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────┐
│                     PRICE FLOW SYSTEM                        │
└─────────────────────────────────────────────────────────────┘


STEP 1: ADMIN SETS PRICE
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────┐
│   Admin Panel Form       │
│   ▼ Category: Instagram  │
│   ▼ Name: Premium Acc    │
│   ▼ Price: 2500.00 ◄─────┼── PRICE ENTERED HERE
│   ▼ Image: [Upload]      │
│   ▼ File: [Upload]       │
└──────────────────────────┘
            │
            ▼
    [Submit Button]
            │
            ▼

STEP 2: VALIDATION
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────┐
│  ProductController.php   │
│  - Check: required ✓     │
│  - Check: numeric ✓      │
│  - Check: > 0 ✓          │
│  ✅ Validation Passed    │
└──────────────────────────┘
            │
            ▼

STEP 3: DATABASE STORAGE
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────┐
│   Products Table         │
│   ┌──────────────────┐   │
│   │ id: 1            │   │
│   │ name: Premium Acc│   │
│   │ price: 2500.00   │◄──┼── STORED IN DATABASE
│   │ category_id: 5   │   │
│   │ status: 1        │   │
│   └──────────────────┘   │
└──────────────────────────┘
            │
            ▼

STEP 4: CUSTOMER VIEW
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────────────┐
│   Product Listing Page           │
│   ┌────────────────────────────┐ │
│   │ 📷 [Product Image]         │ │
│   │ Premium Instagram Accounts │ │
│   │ ₦2,500.00 | 10 pcs in stock│◄┼── PRICE DISPLAYED
│   │ [View Details]             │ │
│   └────────────────────────────┘ │
└──────────────────────────────────┘
            │
            ▼

STEP 5: PRODUCT DETAILS
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────────────┐
│   Product Details Page           │
│   Premium Instagram Accounts     │
│                                  │
│   Price: ₦2,500.00/Pcs ◄─────────┼── UNIT PRICE
│   Stock: 10 pcs                  │
│                                  │
│   Quantity: [▼ 1 ▲]              │
│   Total: ₦2,500.00 ◄─────────────┼── CALCULATED TOTAL
│                                  │
│   [Buy Now]                      │
└──────────────────────────────────┘
            │
            ▼

STEP 6: QUANTITY CALCULATION
━━━━━━━━━━━━━━━━━━━━━━━
User changes quantity to 3:
┌──────────────────────────┐
│  JavaScript Calculation  │
│  unitPrice = 2500.00     │
│  quantity = 3            │
│  total = 2500 × 3        │
│  total = 7500.00 ◄───────┼── NEW TOTAL
└──────────────────────────┘
            │
            ▼

STEP 7: CHECKOUT
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────┐
│   Order Summary          │
│   Product: Premium Acc   │
│   Unit Price: ₦2,500.00  │
│   Quantity: 3            │
│   ─────────────────────  │
│   Subtotal: ₦7,500.00    │
│   Discount: -₦750.00     │◄── OPTIONAL COUPON
│   ─────────────────────  │
│   Total: ₦6,750.00 ◄─────┼── FINAL AMOUNT
└──────────────────────────┘
            │
            ▼

STEP 8: PAYMENT PROCESSING
━━━━━━━━━━━━━━━━━━━━━━━
┌────────────────────────────┐
│  PaymentController.php     │
│                            │
│  amount = price × qty      │
│  amount = 2500 × 3         │
│  amount = 7500.00          │
│                            │
│  IF coupon:                │
│    discount = 10% of 7500  │
│    discount = 750          │
│    amount = 7500 - 750     │
│    amount = 6750.00        │
│                            │
│  user->balance -= 6750.00  │◄── DEDUCTED FROM WALLET
└────────────────────────────┘
            │
            ▼

STEP 9: ORDER CREATED
━━━━━━━━━━━━━━━━━━━━━━━
┌────────────────────────────┐
│   Orders Table             │
│   ┌──────────────────────┐ │
│   │ user_id: 123         │ │
│   │ total_amount: 6750.00│◄┼── FINAL PAID AMOUNT
│   │ status: success      │ │
│   │ name: Premium Acc    │ │
│   └──────────────────────┘ │
└────────────────────────────┘
            │
            ▼

STEP 10: ORDER ITEMS
━━━━━━━━━━━━━━━━━━━━━━━
┌────────────────────────────────┐
│   Order Items Table            │
│   ┌──────────────────────────┐ │
│   │ Item 1                   │ │
│   │ price: 2500.00 ◄─────────┼┼── UNIT PRICE SAVED
│   │ name: Premium Acc        │ │
│   │ product_detail_id: 45    │ │
│   ├──────────────────────────┤ │
│   │ Item 2                   │ │
│   │ price: 2500.00           │ │
│   │ product_detail_id: 46    │ │
│   ├──────────────────────────┤ │
│   │ Item 3                   │ │
│   │ price: 2500.00           │ │
│   │ product_detail_id: 47    │ │
│   └──────────────────────────┘ │
└────────────────────────────────┘

✅ TRANSACTION COMPLETE
```

---

## 🔄 Price Calculation Examples

### Example 1: Simple Purchase
```
Product Price: ₦2,500.00
Quantity: 1
Coupon: None

Calculation:
Total = 2500 × 1 = ₦2,500.00
```

### Example 2: Bulk Purchase
```
Product Price: ₦2,500.00
Quantity: 5
Coupon: None

Calculation:
Total = 2500 × 5 = ₦12,500.00
```

### Example 3: Purchase with Coupon
```
Product Price: ₦2,500.00
Quantity: 3
Coupon: 10% OFF

Calculation:
Subtotal = 2500 × 3 = ₦7,500.00
Discount = 10% of 7500 = ₦750.00
Total = 7500 - 750 = ₦6,750.00
```

### Example 4: Decimal Price
```
Product Price: ₦15.50
Quantity: 10
Coupon: None

Calculation:
Total = 15.50 × 10 = ₦155.00
```

---

## 🎯 Key Points Summary

### ✅ Where Price is SET:
1. **Admin Panel** - Product form (only place where price is manually entered)

### ✅ Where Price is STORED:
2. **Database** - `products` table, `price` column (decimal 28,8)

### ✅ Where Price is DISPLAYED:
3. **Product List** - Shows unit price
4. **Product Details** - Shows unit price and calculated total
5. **Checkout** - Shows final amount (with quantity and discounts)

### ✅ Where Price is CALCULATED:
6. **Frontend (JavaScript)** - Real-time total when quantity changes
7. **Backend (PHP)** - Final calculation during checkout

### ✅ Where Price is USED:
8. **Payment Processing** - To deduct from user wallet
9. **Order Records** - Stored in order and order_items tables
10. **Referral System** - To calculate referral commissions

---

## 🛠️ Price Modification Points

### Can Admin Change Price?
✅ **YES** - Edit product and change price field

### Does it affect old orders?
❌ **NO** - Old orders keep their original price

### Can users negotiate price?
❌ **NO** - Price is fixed (but coupons can reduce it)

### Can price be dynamic?
❌ **NO** - Currently fixed by admin (not automated)

---

## 💡 Important Notes

1. **Currency Symbol**: Comes from `$general->cur_sym` (system-wide setting)
2. **Currency Text**: Comes from `$general->cur_text` (e.g., "NGN", "USD")
3. **Price Format**: Displayed using `showAmount()` helper function
4. **Number Format**: Often formatted with `number_format()` for display

---

## 🔐 Price Security

### Validation Layers:
1. ✅ Frontend validation (required field)
2. ✅ Backend validation (required|numeric|gt:0)
3. ✅ Database constraint (decimal type)
4. ✅ Payment verification (checks user balance)

### Cannot be bypassed:
- User cannot modify price in browser
- Price always fetched from database
- All calculations done server-side
- Frontend calculations are display-only

---

This is the complete price determination flow in your Fadded Socials platform! 🎉


