# Teman E-Commerce

A modern Laravel e-commerce platform with Malaysian payment integration, featuring FPX & QR payments, voucher/promotion system, OCR receipt processing, and comprehensive admin dashboard.

## 🌐 Live Demo
[Live Demo](https://teman-ecommerce-production.up.railway.app/) *(Note: Free trial may expire)*

## 🚀 Tech Stack

**Backend:** Laravel 12, Livewire 3, PHP 8.2+, SQLite/MySQL/PostgreSQL  
**Frontend:** Tailwind CSS 4, Vite, Alpine.js  
**Dependencies:** Spatie Permissions, Tesseract OCR, DomPDF

## ✨ Key Features

- **🏪 Store Frontend**: Product catalog with variants, shopping cart, checkout
- **💳 Payment Integration**: FPX (Malaysian banks) & QR code payments  
- **🎟️ Voucher System**: Coupon codes, promotions, automatic discounts
- **📄 Receipt Processing**: OCR-based payment verification using Tesseract
- **📊 Admin Dashboard**: Analytics, order management, inventory tracking
- **👤 Role Management**: Admin authentication with permission controls
- **📦 Order Management**: Complete lifecycle from pending to fulfillment
- **⚠️ Stock Management**: Real-time inventory with low stock alerts

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

### Install Tesseract OCR
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

## 📁 Project Structure

```
app/
├── Http/Controllers/  # Web controllers
├── Livewire/         # UI components (StorePage, Admin panels, etc.)
├── Models/           # Eloquent models (Product, Order, Voucher, etc.)
└── Services/         # Business logic (DiscountService, ReceiptProcessing, etc.)

resources/views/
├── livewire/         # Livewire component templates
├── admin/           # Admin dashboard views
└── components/       # Reusable Blade components

database/
├── migrations/       # Database schema
└── seeders/         # Sample data
```

## 🚀 Deployment

**Railway (Current):** Auto-deployment from main branch  
**Docker:** `docker build -t teman-ecommerce . && docker run -p 80:80 teman-ecommerce`  
**Heroku/DigitalOcean:** Compatible with standard Laravel deployment  

## 📄 License

MIT License - See [LICENSE](LICENSE) for details.