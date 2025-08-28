# Teman E-Commerce Store

A modern, feature-rich e-commerce system built with Laravel 12 and Livewire 3, featuring comprehensive product management, variant support, order processing, and real-time customer interactions.

## ✨ Features

### 🛍️ Customer Store
- **Product Browsing**: Browse products with real-time stock information
- **Variant Selection**: Choose from multiple product variants (sizes, colors, models)
- **Smart Shopping Cart**: Add products with variant selection and quantity control
- **Real-time Stock Updates**: Live stock validation and quantity limits
- **Single-page Checkout**: Streamlined order placement with customer details
- **Responsive Design**: Mobile-friendly interface built with TailwindCSS

### 🔐 Admin Panel
- **Secure Authentication**: Role-based access control with Spatie Laravel Permission
- **Product Management**: Full CRUD operations with soft delete support
- **Variant Management**: Create and manage product variants with stock levels
- **Order Management**: View, filter, and update order statuses
- **Image Management**: Product image upload and storage
- **Stock Monitoring**: Real-time stock level tracking across variants
- **Advanced Features**: Search, pagination, and status filtering

### 🚀 Technical Features
- **Real-time Updates**: Livewire-powered dynamic interface
- **Soft Deletes**: Safe product and variant deletion with restoration capability
- **Database Transactions**: Secure order processing with rollback support
- **Input Validation**: Comprehensive form validation and sanitization
- **CSRF Protection**: Built-in security measures
- **Responsive UI**: Modern, mobile-first design

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire 3.6 + Blade templates
- **Styling**: TailwindCSS 4.0
- **Database**: SQLite (easily configurable for MySQL/PostgreSQL)
- **Authentication**: Laravel Auth + Spatie Laravel Permission
- **Build Tool**: Vite 7.0
- **Development**: Laravel Pail, Pint, Sail

## 📦 Installation

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
   # Create SQLite database (or configure your preferred database)
   touch database/database.sqlite
   
   # Run migrations
   php artisan migrate
   
   # Seed with sample data
   php artisan db:seed
   ```

6. **Storage setup**
   ```bash
   php artisan storage:link
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🎯 Usage

### Admin Access
- **URL**: `/admin` (redirects to `/admin/products`)
- **Login**: `admin@teman.com` / `admin123`
- **Features**:
  - Manage products and variants
  - Monitor stock levels
  - Process customer orders
  - Update order statuses
  - Restore soft-deleted items

### Customer Store
- **URL**: `/` (root)
- **Features**:
  - Browse products with variants
  - Add items to cart
  - Real-time stock validation
  - Place orders with customer details

## 🗄️ Database Schema

The system uses the following core tables:
- `users` - User accounts with role assignments
- `products` - Product information with soft delete support
- `product_variants` - Product variants with stock levels and soft delete
- `orders` - Customer orders with status tracking
- `order_items` - Individual items in orders
- `permissions` & `roles` - Role-based access control

## 📊 Sample Data

The seeder creates:
- **Admin User**: `admin@teman.com` / `admin123`
- **Sample Products**:
  - Premium T-Shirt (S, M, L, XL variants)
  - Wireless Headphones (Black, White, Blue variants)
  - Smartphone Case (iPhone 13, 14, Samsung variants)
  - Coffee Mug (Red, Blue, Green variants)
- **Stock Levels**: Various quantities for testing

## 🔧 Development

### Running Tests
```bash
php artisan test
```

### Code Quality
```bash
./vendor/bin/pint
```

### Development Server
```bash
# Start all services (Laravel, Vite, Queue, Logs)
composer run dev

# Or individually:
php artisan serve
npm run dev
php artisan queue:work
```

### Livewire Development
- Components: `app/Livewire/`
- Views: `resources/views/livewire/`
- Real-time updates with `wire:` directives

## 🚀 Deployment

1. **Production Environment**
   ```bash
   # Set production variables
   APP_ENV=production
   APP_DEBUG=false
   
   # Optimize for production
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Database Setup**
   - Configure production database credentials
   - Run migrations: `php artisan migrate --force`
   - Seed admin user if needed

3. **Web Server Configuration**
   - Apache/Nginx with PHP-FPM
   - SSL certificate setup
   - File storage configuration (S3, etc.)

4. **Asset Building**
   ```bash
   npm run build
   ```

## 🔒 Security Features

- **Authentication**: Laravel's built-in auth system
- **Authorization**: Role-based permissions with Spatie
- **CSRF Protection**: Automatic on all forms
- **Input Validation**: Comprehensive request validation
- **File Upload Security**: Image type and size restrictions
- **SQL Injection Protection**: Eloquent ORM with prepared statements

## 📱 Features in Detail

### Product Variants
- Multiple variants per product (size, color, model)
- Individual stock tracking per variant
- Real-time stock validation
- Soft delete support with restoration

### Order Processing
- Cart-based order placement
- Customer information collection
- Stock deduction on order completion
- Order status management (pending, processing, shipped, delivered, cancelled)

### Admin Management
- Product CRUD with image support
- Variant creation and stock management
- Order filtering and search
- Soft delete with permanent deletion option

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `php artisan test`
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For issues and questions:
1. Check the [Laravel documentation](https://laravel.com/docs)
2. Review [Livewire documentation](https://livewire.laravel.com/)
3. Examine code comments and structure
4. Check Laravel logs in `storage/logs/`

---

**Built with ❤️ using Laravel 12 and Livewire 3**
