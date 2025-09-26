# Teman E-Commerce

A modern e-commerce platform built with Laravel and Livewire, featuring Malaysian payment integration (FPX & QR), receipt processing with OCR, and comprehensive admin dashboard.

## 🌐 Live Demo
[Live Demo](https://teman-ecommerce-production.up.railway.app/) 
-proly 27/9/2025 kinda dead (free trial mayn)

## 🚀 Tech Stack

### Backend
- **Laravel 12** - PHP framework
- **Livewire 3** - Full-stack framework for dynamic UIs
- **PHP 8.2+** - Server-side language
- **SQLite** - Default database (supports MySQL, PostgreSQL)

### Frontend
- **Tailwind CSS 4** - Utility-first CSS framework
- **Vite** - Build tool and dev server
- **Alpine.js** - Lightweight JavaScript framework (via Livewire)

### Key Features
- **Product Management** - CRUD operations with variants and soft deletes
- **Order Processing** - Complete order lifecycle management
- **Payment Integration** - FPX (Malaysian online banking) and QR code payments
- **Receipt Processing** - OCR-based receipt verification using Tesseract
- **Admin Dashboard** - Real-time analytics and order management
- **Role-based Access** - Admin authentication with Spatie Laravel Permission
- **Stock Management** - Inventory tracking with low stock alerts

### Dependencies
- `spatie/laravel-permission` - Role and permission management
- `thiagoalessio/tesseract_ocr` - OCR for receipt processing
- `laravel/tinker` - REPL for Laravel
- `fakerphp/faker` - Fake data generation

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)
- Tesseract OCR (for receipt processing)

### Installing Tesseract OCR

**Windows (using Chocolatey):**
```bash
choco install tesseract
```

**macOS (using Homebrew):**
```bash
brew install tesseract
```

**Ubuntu/Debian:**
```bash
sudo apt-get install tesseract-ocr
```

## 🛠️ Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd teman_ecommerce
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database setup**
```bash
# For SQLite (default)
touch database/database.sqlite

# For MySQL/PostgreSQL, update .env file with database credentials
```

6. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

7. **Build assets**
```bash
npm run build
```

8. **Start the development server**
```bash
php artisan serve
```

## 🔧 Development

### Development Script
Use the included development script to run all services concurrently:
```bash
composer run dev
```

This starts:
- Laravel development server
- Queue worker
- Log viewer (Pail)
- Vite dev server

### Available Commands
```bash
# Run tests
composer run test

# Code formatting
./vendor/bin/pint

# Clear caches
php artisan optimize:clear
```

## 🗄️ Database Configuration

The application supports multiple database drivers:

### SQLite (Default)
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

### MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teman_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=teman_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

## 🐳 Docker Deployment

The project includes a Dockerfile for containerized deployment:

```bash
# Build the image
docker build -t teman-ecommerce .

# Run the container
docker run -p 80:80 teman-ecommerce
```

## 👤 Admin Access

Default admin credentials:
- **Email:** `admin@teman.com`
- **Password:** `admin123`

## 📁 Project Structure

```
app/
├── Http/Controllers/     # API and web controllers
├── Livewire/            # Livewire components
├── Models/              # Eloquent models
├── Services/            # Business logic services
└── Providers/           # Service providers

resources/
├── views/               # Blade templates
│   ├── livewire/       # Livewire component views
│   └── admin/          # Admin panel views
├── css/                # Stylesheets
└── js/                 # JavaScript files

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
```

## 🔐 Security Features

- CSRF protection
- SQL injection prevention
- XSS protection
- Role-based access control
- Secure file uploads
- Input validation and sanitization

## 📱 Payment Integration

### FPX (Malaysian Online Banking)
- Integration with Malaysian banks
- Real-time payment verification
- Callback handling

### QR Code Payments
- Generate QR codes for payments
- Mobile payment integration
- Receipt verification

## 🔍 Receipt Processing

The application includes OCR-based receipt processing:
- Upload payment receipts
- Extract reference numbers using Tesseract
- Automatic payment verification
- Support for multiple image formats

## 🚀 Deployment

### Railway (Current)
The application is deployed on Railway with automatic deployments from the main branch.

### Other Platforms
- **Heroku:** Use the included Dockerfile
- **DigitalOcean:** App Platform compatible
- **VPS:** Standard Laravel deployment process

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
