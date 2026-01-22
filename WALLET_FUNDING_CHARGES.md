# Wallet Funding Charges & Name Field

## Charges Structure

### For CheckoutNow (Gateway Code: 121)
- **Fixed Charge**: ₦50
- **Percent Charge**: 1.0%
- **Formula**: `Charge = 50 + (Amount × 0.01)`
- **Example**: 
  - Amount: ₦5,000 → Charge: ₦50 + ₦50 = ₦100 → Total: ₦5,100
  - Amount: ₦10,000 → Charge: ₦50 + ₦100 = ₦150 → Total: ₦10,150

### For Manual Payments (Gateway Code: 1000)
- **Amount < ₦5,000**: No charges
- **Amount ≥ ₦5,000**: 1.5% of amount
- **Example**:
  - Amount: ₦3,000 → Charge: ₦0 → Total: ₦3,000
  - Amount: ₦10,000 → Charge: ₦150 → Total: ₦10,150

### For Other Automatic Gateways (XtraPay, PayVibe, etc.)
- **Amount < ₦5,000**: No charges
- **Amount ₦5,000 - ₦20,000**: Fixed charge of ₦100
- **Amount > ₦20,000**: Fixed charge of ₦150 + 1.5% of amount
- **Example**:
  - Amount: ₦3,000 → Charge: ₦0 → Total: ₦3,000
  - Amount: ₦10,000 → Charge: ₦100 → Total: ₦10,100
  - Amount: ₦50,000 → Charge: ₦150 + ₦750 = ₦900 → Total: ₦50,900

## Name Field for CheckoutNow

### Why It's Required
CheckoutNow (CheckoutPay) requires the payer's name to generate a virtual account. The name must match the name on the user's bank account for successful payment matching.

### How It Works
1. **User selects CheckoutNow** as payment method
2. **Name field appears** automatically (only for CheckoutNow)
3. **User enters their name** as it appears on their bank account
4. **Name is sent to CheckoutPay API** when creating payment request
5. **If name not provided**, system uses user's profile name (firstname + lastname)

### Implementation Details
- Name field is **conditionally shown** only when CheckoutNow is selected
- Field is **required** when CheckoutNow is selected
- Name is stored in deposit `detail` field as JSON
- If user doesn't provide name, system falls back to user's profile name

## Code Changes Made

### 1. PaymentController.php
- Added CheckoutNow-specific charge calculation (uses gateway's own charges)
- Stores payer name in deposit detail when provided

### 2. deposit_new.blade.php
- Added payer name input field
- Field shows/hides based on selected gateway
- Required validation for CheckoutNow

### 3. CheckoutNow/ProcessController.php
- Reads payer name from deposit detail or user profile
- Validates payer name is not empty
- Sends payer name to CheckoutPay API

## Testing

To test the charges:
1. Go to wallet funding page
2. Enter different amounts
3. Select CheckoutNow
4. Check the calculated charges match the formula above

To test the name field:
1. Select CheckoutNow gateway
2. Verify name field appears
3. Enter a name
4. Complete the deposit
5. Check that the name is sent to CheckoutPay API
