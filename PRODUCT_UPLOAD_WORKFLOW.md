# Product Upload Workflow - Visual Guide

## Upload Process Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    ADMIN PRODUCT UPLOAD                      │
└─────────────────────────────────────────────────────────────┘

STEP 1: Prepare TXT File
━━━━━━━━━━━━━━━━━━━━━━━
📄 accounts.txt
├─ Line 1: Username:user1 | Password:pass1
├─ Line 2: Username:user2 | Password:pass2
├─ Line 3: Username:user3 | Password:pass3
├─ Line 4: Username:user4 | Password:pass4
└─ Line 5: Username:user5 | Password:pass5

                    ↓

STEP 2: Fill Form Fields
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────────────────────┐
│  🏷️  Category: Instagram Accounts        │
│  📝 Name: Premium Instagram Accounts     │
│  💰 Price: $25.00                        │
│  🖼️  Image: [Upload product.jpg]         │
│  📂 File: [Upload accounts.txt]          │
│  📄 Description: [Rich text editor]      │
│                                          │
│         [Submit Button]                  │
└──────────────────────────────────────────┘

                    ↓

STEP 3: System Processing
━━━━━━━━━━━━━━━━━━━━━━━
System reads accounts.txt and processes:

Line 1 → ProductDetail #1 (status: unsold)
Line 2 → ProductDetail #2 (status: unsold)
Line 3 → ProductDetail #3 (status: unsold)
Line 4 → ProductDetail #4 (status: unsold)
Line 5 → ProductDetail #5 (status: unsold)

Product Created with Stock: 5 units

                    ↓

STEP 4: Product is Live
━━━━━━━━━━━━━━━━━━━━━━━
┌──────────────────────────────────────────┐
│  ✅ Product Active                        │
│  📦 In Stock: 5 units                     │
│  💵 Price: $25.00                         │
│  🛒 Ready for Purchase                    │
└──────────────────────────────────────────┘
```

---

## Customer Purchase Flow

```
CUSTOMER BUYS PRODUCT
━━━━━━━━━━━━━━━━━━━━━━━

User selects product → Adds to cart → Makes payment

                    ↓

SYSTEM PROCESSES ORDER
━━━━━━━━━━━━━━━━━━━━━━━

1. Find FIRST unsold ProductDetail
   → ProductDetail #1 (Username:user1 | Password:pass1)

2. Mark as SOLD
   ✅ ProductDetail #1 → status: SOLD

3. Deliver to customer
   📧 Customer receives: "Username:user1 | Password:pass1"

4. Update stock
   📦 In Stock: 5 → 4 units

                    ↓

NEXT PURCHASE
━━━━━━━━━━━━━━━━━━━━━━━

Next customer buys → Gets ProductDetail #2
Next customer buys → Gets ProductDetail #3
...and so on until stock runs out
```

---

## Stock Management Visual

```
INITIAL STATE (After Upload)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Product: "Premium Instagram Accounts"
Stock: 5 units available

┌─────────────────────────────────────────┐
│ ProductDetail #1 | 🟢 UNSOLD            │
│ ProductDetail #2 | 🟢 UNSOLD            │
│ ProductDetail #3 | 🟢 UNSOLD            │
│ ProductDetail #4 | 🟢 UNSOLD            │
│ ProductDetail #5 | 🟢 UNSOLD            │
└─────────────────────────────────────────┘


AFTER 2 SALES
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Product: "Premium Instagram Accounts"
Stock: 3 units available

┌─────────────────────────────────────────┐
│ ProductDetail #1 | 🔴 SOLD (to User A)  │
│ ProductDetail #2 | 🔴 SOLD (to User B)  │
│ ProductDetail #3 | 🟢 UNSOLD            │
│ ProductDetail #4 | 🟢 UNSOLD            │
│ ProductDetail #5 | 🟢 UNSOLD            │
└─────────────────────────────────────────┘


RESTOCKING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Admin uploads new file with 3 more accounts

┌─────────────────────────────────────────┐
│ ProductDetail #1 | 🔴 SOLD              │
│ ProductDetail #2 | 🔴 SOLD              │
│ ProductDetail #3 | 🟢 UNSOLD            │
│ ProductDetail #4 | 🟢 UNSOLD            │
│ ProductDetail #5 | 🟢 UNSOLD            │
│ ProductDetail #6 | 🟢 UNSOLD (NEW)      │
│ ProductDetail #7 | 🟢 UNSOLD (NEW)      │
│ ProductDetail #8 | 🟢 UNSOLD (NEW)      │
└─────────────────────────────────────────┘

Stock: 6 units available
```

---

## File Format Comparison

### ✅ CORRECT FORMAT

```txt
Username:account1 | Password:pass123
Username:account2 | Password:pass456
Username:account3 | Password:pass789
```
**Result**: 3 units in stock ✅


### ❌ WRONG FORMAT (Extra blank lines)

```txt
Username:account1 | Password:pass123

Username:account2 | Password:pass456

Username:account3 | Password:pass789

```
**Result**: Issues with counting/processing ❌


### ✅ ALTERNATIVE CORRECT FORMATS

**Email Format:**
```txt
Email:user1@gmail.com | Password:pass123
Email:user2@gmail.com | Password:pass456
```

**Custom Format:**
```txt
Login: user1 | Pass: pass123 | Email: test@mail.com
Login: user2 | Pass: pass456 | Email: demo@mail.com
```

**Simple Format:**
```txt
user1:pass123
user2:pass456
user3:pass789
```

All these work as long as:
- One account per line
- No blank lines
- Plain text file

---

## Admin Panel Product Management

```
ADMIN DASHBOARD → Products
━━━━━━━━━━━━━━━━━━━━━━━━━━━━

┌─────────────────────────────────────────────────────────────┐
│ All Products                                    [+ Add New] │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ Name                  | Price  | Stock | Status | Actions   │
│ ────────────────────────────────────────────────────────── │
│ Instagram Accounts    | $25.00 |   5   | Active | [Edit]   │
│   → Fadded VIP 🔆     |        |       |        | [View]   │
│                       |        |       |        | [Delete] │
│ ────────────────────────────────────────────────────────── │
│ TikTok Accounts       | $30.00 |   10  | Active | [Edit]   │
│   → Fadded VIP 🔆     |        |       |        | [View]   │
│                       |        |       |        | [Delete] │
│ ────────────────────────────────────────────────────────── │
│ Facebook Accounts     | $20.00 |   0   | Active | [Edit]   │
│   → Fadded VIP 🔆     |        |       |        | [View]   │
│                       |        |       |        | [Delete] │
│                       |        |       |        | [Restock]│
└─────────────────────────────────────────────────────────────┘


CLICKING "View Accounts"
━━━━━━━━━━━━━━━━━━━━━━━━━━━━

┌─────────────────────────────────────────────────────────────┐
│ Instagram Accounts - In Stock (5)                           │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│ Details                               | Status  | Actions   │
│ ────────────────────────────────────────────────────────── │
│ Username:user1 | Password:pass1       | 🔴 SOLD | [View]   │
│ Username:user2 | Password:pass2       | 🔴 SOLD | [View]   │
│ Username:user3 | Password:pass3       | 🟢 UNSOLD| [Edit]  │
│ Username:user4 | Password:pass4       | 🟢 UNSOLD| [Edit]  │
│ Username:user5 | Password:pass5       | 🟢 UNSOLD| [Edit]  │
└─────────────────────────────────────────────────────────────┘
```

---

## Quick Reference Card

### Product Upload Checklist

```
□ Step 1: Create TXT file with accounts
          Format: One account per line
          Example: Username:user1 | Password:pass1

□ Step 2: Prepare product image
          Format: JPG, PNG, or JPEG
          Size: Will be auto-resized

□ Step 3: Go to Admin → Products → Add New

□ Step 4: Fill required fields
          ✓ Category (dropdown)
          ✓ Name (text)
          ✓ Price (number)
          ✓ Image (upload)
          ✓ Accounts file (upload TXT)
          ○ Description (optional)

□ Step 5: Submit form

□ Step 6: Verify product appears in list

□ Step 7: Check stock count matches
          Number of lines in TXT = Stock count
```

---

## Data Flow Diagram

```
                    ADMIN UPLOADS TXT
                          │
                          ▼
              ┌───────────────────────┐
              │   File Processing     │
              │   Split by newlines   │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │  Create ProductDetail │
              │  for EACH line        │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │   Store in Database   │
              │   - product_id        │
              │   - details (line)    │
              │   - is_sold = 0       │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │   Calculate Stock     │
              │   Count unsold items  │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │  Display on Frontend  │
              │  Users can purchase   │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │   CUSTOMER BUYS       │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │  Find First Unsold    │
              │  ProductDetail        │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │  Mark as SOLD         │
              │  is_sold = 1          │
              └───────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │  Deliver to Customer  │
              │  Show credential line │
              └───────────────────────┘
```

---

This visual workflow helps understand the complete product upload and sales process!


