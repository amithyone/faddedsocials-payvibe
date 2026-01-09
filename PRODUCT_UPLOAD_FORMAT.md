# Admin Product Upload Format Guide

## Overview
The admin uploads products to the Fadded Socials platform using a combination of form fields and a TXT file containing account credentials/details.

---

## Product Upload Fields

### 1. **Product Image** (Required)
- **File Type**: JPG, JPEG, PNG
- **Purpose**: Display image for the product (shown to customers)
- **Requirement**: Required when creating a new product, optional when updating

### 2. **Category** (Required)
- **Type**: Dropdown selection
- **Options**: Pre-created categories (e.g., "Fadded VIP 🔆 Instagram Accounts", "Fadded VIP 🔆 TikTok Accounts")
- **Requirement**: Must select one category

### 3. **Product Name** (Required)
- **Type**: Text field
- **Example**: "Premium Instagram Accounts - 10K Followers"
- **Requirement**: Required

### 4. **Price** (Required)
- **Type**: Numeric (decimal allowed)
- **Currency**: System currency (e.g., $, ₦, etc.)
- **Example**: 5000.00
- **Requirement**: Must be greater than 0

### 5. **Accounts File** (Required for new products)
- **File Type**: `.txt` (plain text file)
- **Format**: Each account/credential on a new line
- **Requirement**: Required when creating, optional when updating
- **Purpose**: Contains the actual product details/credentials to be sold

### 6. **Description** (Optional)
- **Type**: Rich text editor (supports HTML)
- **Purpose**: Product details shown to customers before purchase
- **Example**: "High-quality Instagram accounts with verified email access"

---

## TXT File Format (Most Important!)

### Format Structure
The TXT file must follow this exact format:

```
Username:username1 | Password:password1
Username:username2 | Password:password2
Username:username3 | Password:password3
```

### Format Rules:
1. **One account per line** - Each line represents ONE product detail/credential
2. **New line separator** - Press Enter/Return after each account
3. **Line format**: Can be any text format, but commonly used:
   - `Username:value | Password:value`
   - `Email:value | Password:value`
   - `Login:value | Pass:value`
   - Or any custom format needed for that product type

### Example TXT File Contents:

#### Example 1: Instagram Accounts
```
Username:insta_user1 | Password:SecurePass123
Username:insta_user2 | Password:MyPass456
Username:insta_user3 | Password:Account789
Username:insta_user4 | Password:Login2024
```

#### Example 2: Email Accounts
```
Email:user1@gmail.com | Password:Pass123!@#
Email:user2@gmail.com | Password:MySecure456
Email:user3@yahoo.com | Password:Account789
```

#### Example 3: Game Accounts
```
Username:gamer123 | Password:GamePass1 | Level:50
Username:progamer | Password:SecureGame2 | Level:75
Username:topplayer | Password:MyGame3 | Level:100
```

#### Example 4: Custom Format
```
Account ID: 12345 | Email: test@email.com | Password: Pass123
Account ID: 67890 | Email: user@email.com | Password: Secure456
```

---

## How It Works

### Backend Processing:
1. **Admin uploads the TXT file**
2. **System reads the file** and splits it by new lines (`\n`)
3. **Each line becomes a ProductDetail record** in the database
4. **Each ProductDetail tracks**:
   - The product it belongs to
   - The credential/detail text
   - Whether it's sold or unsold
5. **When a user buys the product**:
   - System finds the first unsold ProductDetail
   - Marks it as sold
   - Delivers that specific line to the customer

### Stock Management:
- **In Stock Count** = Number of unsold ProductDetails
- If you upload 100 lines, you have 100 units in stock
- Each sale reduces stock by 1
- Admin can view all accounts (sold/unsold) from the admin panel

---

## Complete Product Upload Example

### Step-by-Step Process:

#### 1. Prepare Your TXT File
Create a file named `instagram_accounts.txt`:
```
Username:premium_insta1 | Password:SecurePass1!
Username:premium_insta2 | Password:SecurePass2!
Username:premium_insta3 | Password:SecurePass3!
Username:premium_insta4 | Password:SecurePass4!
Username:premium_insta5 | Password:SecurePass5!
```

#### 2. Prepare Product Image
- Save an attractive image (e.g., `instagram-product.jpg`)
- Recommended: Eye-catching, branded image
- Image will be resized automatically

#### 3. Fill Form Fields
- **Category**: Select "Fadded VIP 🔆 Instagram Accounts"
- **Name**: "Premium Instagram Accounts - Active Users"
- **Price**: 2500.00
- **Image**: Upload `instagram-product.jpg`
- **Accounts File**: Upload `instagram_accounts.txt`
- **Description**: 
  ```
  ✅ Premium Instagram accounts
  ✅ Verified email access
  ✅ No bans or restrictions
  ✅ Ready to use immediately
  ✅ 100% satisfaction guaranteed
  ```

#### 4. Submit
- Click "Submit" button
- System creates the product
- 5 units will be in stock (5 lines in TXT file)
- Product appears in the marketplace

---

## Demo Format Download

The system provides a demo TXT file download at:
- **Route**: `admin.product.download.demo.txt`
- **Content**:
```
Username:username | Password:username
Username:username | Password:username
```

This helps admins understand the format before uploading real accounts.

---

## Important Notes

### ✅ DO:
- Use plain text (.txt) files only
- Put each account on a new line
- Test with 2-3 accounts first
- Keep backup of uploaded files
- Use clear, consistent formatting

### ❌ DON'T:
- Don't use Word documents (.doc, .docx)
- Don't use Excel files (.xls, .xlsx)
- Don't leave blank lines in the file
- Don't include special characters that might break the format
- Don't upload files without testing the format first

---

## Database Structure

### Products Table:
```
- id
- category_id (link to category)
- name
- price
- description
- image
- status (active/inactive)
```

### Product Details Table:
```
- id
- product_id (link to product)
- details (the actual credential line from TXT)
- is_sold (0 = unsold, 1 = sold)
- status
```

---

## Admin Workflow

1. **Create/Manage Categories** first (if not exists)
2. **Navigate to Products** → Add New
3. **Fill all required fields**
4. **Upload product image** (JPG/PNG)
5. **Upload accounts TXT file** (formatted correctly)
6. **Add description** (optional but recommended)
7. **Submit the form**
8. **Product is now live** with stock count
9. **Monitor stock levels** from product list
10. **Add more stock** by editing product and uploading new TXT file

---

## Updating Products

When updating an existing product:
- **Image**: Optional (only upload if changing image)
- **Accounts File**: Optional (only upload to add more stock)
- **Other fields**: Can be updated anytime

**Adding More Stock**:
- Edit the product
- Upload a new TXT file with additional accounts
- New accounts are added to existing stock
- System doesn't delete old unsold accounts

---

## Common Issues & Solutions

### Issue: "File type not supported"
**Solution**: Ensure file extension is `.txt` (not `.doc` or `.rtf`)

### Issue: Stock count is wrong
**Solution**: Check TXT file for blank lines, remove them

### Issue: Accounts not showing
**Solution**: Verify each account is on a new line with proper line breaks

### Issue: Special characters appear broken
**Solution**: Save TXT file with UTF-8 encoding

---

## Best Practices

1. **Consistent Format**: Use the same format for all accounts in a category
2. **Quality Control**: Test accounts before uploading
3. **Clear Descriptions**: Help customers understand what they're buying
4. **Good Images**: Use attractive, professional product images
5. **Stock Management**: Monitor and restock popular products
6. **Pricing Strategy**: Price competitively based on account quality
7. **Category Organization**: Keep products well-organized by category

---

This format ensures smooth product management and delivery for the Fadded Socials marketplace!



