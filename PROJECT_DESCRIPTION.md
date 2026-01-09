# Fadded Socials - Digital Marketplace Platform

## Project Overview

**Fadded Socials** is a comprehensive Laravel-based digital marketplace platform that enables users to browse, purchase, and manage digital products - specifically focused on social media accounts and related digital goods. The platform provides a complete e-commerce solution with integrated payment processing, wallet management, order tracking, and user engagement features.

## Core Business Model

The platform operates as a **digital goods marketplace** where:
- **Sellers/Admins** can list digital products organized by categories (e.g., "Fadded VIP 🔆 Instagram Accounts", "Fadded VIP 🔆 TikTok Accounts", etc.)
- **Buyers** can browse products, make purchases using multiple payment methods, and receive instant digital delivery
- Each product can have multiple **product details/credentials** that are sold individually (inventory tracking)
- Orders are tracked and managed through a comprehensive dashboard

## Key Features

### 1. Product Management
- **Categorized Products**: Digital products organized into service categories with custom branding ("Fadded VIP 🔆" prefix)
- **Product Inventory**: Each product contains multiple product details (credentials/accounts) tracked as sold/unsold
- **Search & Filter**: Advanced search functionality to find products by category, name, or description
- **Product Details**: Detailed product pages with descriptions, pricing, and availability status

### 2. User Account & Authentication
- User registration and login system
- Social media authentication (OAuth via Socialite)
- Email and mobile verification
- Password reset functionality
- Two-factor authentication support
- Profile management

### 3. Payment & Wallet System
- **Multiple Payment Gateways**:
  - PayVibe (primary integration)
  - PayStack
  - Stripe
  - Razorpay
  - Authorize.Net
  - Bitcoin (BTCPayServer)
  - CoinGate (cryptocurrency)
  - Mollie
  - And more...
- **Wallet System**: Users can maintain wallet balances for quick purchases
- **Deposit Management**: Fund wallet with multiple payment options
- **Cash-out Functionality**: Withdraw funds from wallet
- **Transaction History**: Complete payment and deposit tracking

### 4. Shopping & Order Management
- **Shopping Cart**: Add products to cart and checkout
- **Order Processing**: Automated order creation and processing
- **Order Tracking**: Real-time order status tracking
- **Order History**: Complete purchase history with details
- **Order Items**: Each order can contain multiple product items
- **Instant Delivery**: Digital products delivered immediately upon payment confirmation

### 5. Gift & Referral System
- **Gift Orders**: Send digital products as gifts to other users
- **Referral Program**: Users can refer others and earn rewards
- **Referral Tracking**: Track referral codes, earnings, and withdrawals
- **Fund Transfer**: Send funds to other users within the platform

### 6. User Dashboard
- **Dashboard Overview**: 
  - Total payments made
  - Total orders placed
  - Support tickets count
  - Recent transaction history
- **Wallet Balance**: Quick access to current wallet balance
- **Quick Actions**: Easy access to key features (deposit, purchase, track orders)

### 7. Support & Communication
- **Support Ticket System**: Create and manage support tickets
- **Ticket Attachments**: Upload files to support tickets
- **Email Notifications**: Automated notifications for order updates, payments, etc.
- **SMS Notifications**: SMS support via multiple gateways (Twilio, Vonage, etc.)
- **Contact Form**: Direct contact with admin/support team

### 8. Admin Panel
- Complete admin dashboard for managing:
  - Products and categories
  - Orders and transactions
  - Users and user management
  - Payment gateways configuration
  - Support tickets
  - System settings
  - Content management (blogs, pages, policies)

### 9. Content Management
- **Blog System**: Content blog with categories and details
- **Static Pages**: Customizable pages (Terms of Use, Privacy Policy, etc.)
- **Dynamic Sections**: Page sections for homepage and other pages
- **Multi-language Support**: Language switching and translation support

### 10. Additional Features
- **Responsive Design**: Mobile-first, mobile-optimized UI (primary focus)
- **PWA Support**: Progressive Web App capabilities
- **Search Functionality**: Site-wide search for products and categories
- **Coupon Codes**: Discount code system
- **Notification System**: Custom alerts (no default browser alerts)
- **Cookie Management**: Cookie consent and policy
- **Subscriber Management**: Newsletter subscription system

## Technical Architecture

### Technology Stack
- **Backend**: Laravel 9.x (PHP 8.1+)
- **Frontend**: Blade templating with custom JavaScript
- **Database**: MySQL/PostgreSQL
- **Payment Processing**: Multiple gateway integrations
- **Image Processing**: Intervention Image
- **Email**: PHPMailer, SendGrid, Mailjet
- **SMS**: Twilio, Vonage, TextMagic, MessageBird
- **Authentication**: Laravel Sanctum, Socialite

### Database Models
- **User Management**: Users, User Login Tracking
- **Products**: Products, ProductDetails, Categories
- **Orders**: Orders, OrderItems, TrackOrder
- **Payments**: Deposits, Gateway, GatewayCurrency
- **Gifts**: GiftItem, GiftOrder
- **Support**: SupportTicket, SupportMessage, SupportAttachment
- **Referrals**: Referal, Referre
- **Content**: Frontend, Page, Blog
- **System**: Admin, AdminNotification, Language

## UI/UX Requirements

### Design Principles
1. **Mobile-First**: Interface optimized for mobile devices (primary user base)
2. **Clean & Modern**: Beautiful, intuitive interface with smooth interactions
3. **Brand Consistency**: "Fadded VIP 🔆" branding throughout service categories
4. **Custom Notifications**: Use custom alert components instead of browser alerts
5. **Fast & Responsive**: Quick load times and smooth navigation
6. **Accessible**: User-friendly for all skill levels

### Key UI Components Needed
- **Homepage**: Hero section, featured products, categories showcase
- **Product Catalog**: Category grid/list view, product cards, search interface
- **Product Details**: Product showcase, pricing, add to cart, related products
- **Shopping Cart**: Cart items, checkout flow, payment selection
- **User Dashboard**: Stats widgets, recent activity, quick actions
- **Wallet Interface**: Balance display, deposit options, transaction history
- **Order Tracking**: Status indicators, timeline view, order details
- **Profile Management**: User info, avatar upload, preferences
- **Payment Forms**: Gateway-specific payment forms, wallet top-up

### User Flows
1. **Browse → Search → View → Purchase → Track**
2. **Register → Verify → Deposit → Shop → Track Orders**
3. **Referral → Share Code → Earn → Withdraw**
4. **Gift → Select Product → Enter Recipient → Send**

## Integration Points

### Payment Gateways
- Webhook handling for payment confirmations
- IPN (Instant Payment Notification) processing
- Payment status verification
- Automatic wallet crediting

### Notification Systems
- Email notifications (order confirmations, payment receipts)
- SMS notifications (transaction alerts)
- In-app notifications (custom alert system)

## Security Features
- CSRF protection
- XSS prevention
- SQL injection prevention
- Secure password hashing
- Payment gateway security
- File upload validation
- CAPTCHA verification
- Rate limiting

## Success Metrics
- **User Engagement**: Time on site, pages per session
- **Conversion Rate**: Browsers to buyers ratio
- **Order Completion**: Successful order rate
- **Payment Success**: Payment gateway success rate
- **User Retention**: Return user percentage
- **Mobile Usage**: Mobile vs desktop usage stats

## Development Priorities for UI

1. **High Priority**:
   - Mobile-responsive product catalog
   - Streamlined checkout process
   - User dashboard with clear stats
   - Payment/wallet interface
   - Order tracking visualization

2. **Medium Priority**:
   - Search and filter UI
   - Profile management interface
   - Support ticket interface
   - Referral program dashboard

3. **Nice to Have**:
   - Product comparison
   - Wishlist functionality
   - Advanced filtering
   - Product recommendations

---

This platform serves as a complete digital marketplace solution specifically designed for selling social media accounts and digital goods, with a focus on user experience, payment flexibility, and operational efficiency.

