# Teman E-Commerce

A comprehensive Laravel e-commerce platform designed for Malaysian market with advanced payment integration, intelligent voucher/promotion system, OCR receipt processing, and real-time admin analytics dashboard.

## 🌐 Live Demo
[Live Demo](https://teman-ecommerce-production.up.railway.app/) *(Note: Free trial may expire)*

## 🚀 Tech Stack

**Backend:** Laravel 12, Livewire 3, PHP 8.2+, SQLite/MySQL/PostgreSQL  
**Frontend:** Tailwind CSS 4, Vite, Alpine.js  
**Dependencies:** Spatie Permissions, Tesseract OCR, DomPDF, Google Cloud Vision API  
**Infrastructure:** Docker-ready, Railway deployment, Apache/Nginx compatible

## ✨ Key Features

### 🏪 Store Frontend
- **Product Catalog**: Multi-variant products with real-time stock management
- **Shopping Cart**: Persistent cart with quantity management and variant selection
- **Checkout Process**: Streamlined checkout with customer/shipping information
- **Responsive Design**: Mobile-first design with Tailwind CSS

### 💳 Payment Integration
- **QR Code Payments**: Upload receipt with OCR verification for Malaysian banks
- **FPX Integration**: Malaysian online banking (framework ready, currently disabled)
- **Receipt Processing**: Advanced OCR using Tesseract + Google Cloud Vision
- **Payment Verification**: Automatic reference ID extraction from receipts

### 🎟️ Voucher & Promotion System
- **Coupon Codes**: Percentage/fixed discounts with usage limits
- **Promotional Campaigns**: Banner promotions with priority management
- **Automatic Discounts**: Smart discount application based on cart rules
- **Customer Vouchers**: Public voucher claiming system
- **Admin Management**: Complete voucher/promotion lifecycle management

### 📊 Admin Dashboard & Analytics
- **Sales Overview**: Interactive charts with configurable time periods
- **Order Statistics**: Real-time order metrics and revenue tracking
- **Product Analytics**: Stock levels, top products, inventory valuation
- **Low Stock Alerts**: Automated alerts for inventory management
- **Recent Orders**: Live order monitoring with status updates

### 🛠️ Management Features
- **Product Management**: Full CRUD with variants, soft deletes, restoration
- **Order Management**: Complete order lifecycle from pending to delivered
- **Branding Settings**: Customizable logo, favicon, contact information
- **QR Payment Settings**: Configurable QR code and bank account details
- **User Management**: Admin authentication with Spatie permissions

## 🛠️ Quick Setup

1. **Clone & Install**
```bash
git clone <repository-url>
cd teman_ecommerce
composer install
npm install
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite  # Or configure MySQL/PostgreSQL
```

3. **Initialize Database**
```bash
php artisan migrate
php artisan db:seed
```

4. **Build & Start**
```bash
npm run build  # or 'npm run dev' for development
php artisan serve
```

### 🔧 OCR Setup (Optional but Recommended)

For receipt processing features, install Tesseract OCR:

```bash
# Windows (Chocolatey)
choco install tesseract

# macOS (Homebrew)  
brew install tesseract

# Ubuntu/Debian
sudo apt-get install tesseract-ocr
```

**Google Cloud Vision API (Optional):**
Add to `.env` for enhanced OCR accuracy:
```env
GOOGLE_VISION_ENABLED=true
GOOGLE_VISION_CREDENTIALS_PATH=/path/to/service-account.json
```

## 🔧 Development

**Single Command Development:**
```bash
composer run dev  # Starts server, queue, logs, and Vite dev server
```

**Other Commands:**
```bash
composer run test     # Run tests
./vendor/bin/pint     # Code formatting
php artisan optimize:clear  # Clear caches
```

## 🗄️ Database Options

**SQLite (Default):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

**MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teman_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

**PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=teman_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

## 📋 System Requirements

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- SQLite (default) or MySQL/PostgreSQL
- **Tesseract OCR** (for receipt processing)

### 🔍 OCR Receipt Processing

The platform includes advanced OCR capabilities for Malaysian bank receipts:

**Supported Banks:**
- Maybank, CIMB, Public Bank, RHB
- Hong Leong, Bank Islam, AmBank, Alliance Bank
- Generic pattern matching for other banks

**Features:**
- Automatic reference ID extraction
- Multi-language support (English)
- Pattern recognition for transaction references
- Fallback to manual input if OCR fails
- Support for JPG, PNG, and PDF receipts

**Install Tesseract OCR:**
```bash
# Windows (Chocolatey)
choco install tesseract

# macOS (Homebrew)  
brew install tesseract

# Ubuntu/Debian
sudo apt-get install tesseract-ocr
```

## 👤 Default Access

**Admin Panel:**
- Email: `admin@teman.com`
- Password: `admin123`

Access admin features at `/admin` after login.

## 📊 Admin Dashboard Features

### Analytics & Reporting
- **Sales Overview**: Interactive charts with 7/30/90 day periods
- **Order Statistics**: Real-time metrics (total, pending, processing, delivered)
- **Product Analytics**: Stock levels, inventory valuation, top performers
- **Revenue Tracking**: Daily/monthly revenue with average order values

### Management Tools
- **Product Management**: Full CRUD with variants, soft deletes, bulk operations
- **Order Management**: Status updates, payment verification, e-invoice generation
- **Voucher System**: Create/edit coupons with usage limits and expiration
- **Promotion Management**: Banner campaigns with priority and scheduling
- **Branding Settings**: Logo, favicon, contact info, social media links
- **QR Payment Setup**: Bank account details and QR code configuration

### Inventory Management
- **Low Stock Alerts**: Automated notifications for low inventory
- **Stock Tracking**: Real-time stock levels across all variants
- **Product Variants**: Size, color, and custom variant management
- **Bulk Operations**: Mass updates for pricing and stock levels

## 📁 Project Structure

```
app/
├── Http/Controllers/     # Web controllers (Store, Admin, Order, Auth)
├── Livewire/           # Interactive UI components
│   ├── Admin/          # Admin dashboard components
│   │   ├── VoucherManagement.php
│   │   ├── PromotionManagement.php
│   │   ├── BrandingSettings.php
│   │   └── QrPaymentSettings.php
│   ├── StorePage.php   # Main store interface
│   ├── Dashboard.php   # Admin dashboard
│   ├── SalesOverview.php
│   ├── OrderStats.php
│   ├── ProductStats.php
│   ├── LowStockAlert.php
│   ├── TopProducts.php
│   ├── RecentOrders.php
│   ├── QrPaymentPage.php
│   ├── FpxPaymentPage.php
│   └── CustomerVouchers.php
├── Models/             # Eloquent models
│   ├── Product.php & ProductVariant.php
│   ├── Order.php & OrderItem.php
│   ├── Voucher.php & Promotion.php
│   ├── Discount.php & OrderDiscount.php
│   └── WebsiteSettings.php
├── Services/           # Business logic
│   ├── DiscountService.php
│   ├── ReceiptProcessingService.php
│   └── EInvoiceService.php
└── Mail/               # Email templates

resources/views/
├── livewire/           # Livewire component templates
├── admin/             # Admin dashboard views
├── components/        # Reusable Blade components
└── emails/            # Email templates

database/
├── migrations/         # Database schema (15+ migrations)
└── seeders/           # Sample data (AdminSeeder, DatabaseSeeder)
```

## 🚀 Deployment

### Railway (Current Setup)
- Auto-deployment from main branch
- Environment variables configured via Railway dashboard
- Built-in PostgreSQL database
- Automatic SSL certificates

### Docker Deployment
```bash
docker build -t teman-ecommerce .
docker run -p 80:80 teman-ecommerce
```

### Traditional Hosting
**Requirements:**
- PHP 8.2+ with extensions: pdo, mbstring, openssl, tokenizer, xml, ctype, json, bcmath
- Web server (Apache/Nginx)
- Database (MySQL/PostgreSQL/SQLite)
- Composer & Node.js for build process

**Steps:**
1. Upload files to web root
2. Run `composer install --no-dev --optimize-autoloader`
3. Run `npm run build`
4. Configure `.env` file
5. Run `php artisan migrate --force`
6. Set proper permissions for `storage/` and `bootstrap/cache/`

### Environment Variables
```env
APP_NAME="Teman E-Commerce"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teman_ecommerce
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Optional OCR Enhancement
GOOGLE_VISION_ENABLED=false
GOOGLE_VISION_CREDENTIALS_PATH=/path/to/service-account.json
```  

## 📄 License

MIT License - See [LICENSE](LICENSE) for details.