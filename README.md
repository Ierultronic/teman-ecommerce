# Mini E-Commerce Store

A simple e-commerce system built with Laravel and Livewire, featuring product management, variant support, and order processing.

## Features

### Admin Side
- **Product Management**: Create, edit, and delete products
- **Variant Support**: Add multiple variants (sizes, colors, etc.) with stock levels
- **Order Management**: View and update order statuses
- **Image Upload**: Product image management
- **Authentication**: Secure admin login

### Customer Side
- **Product Browsing**: View products with variants and stock information
- **Shopping Cart**: Add products to cart with quantity selection
- **Order Placement**: Single-page checkout with customer details
- **Real-time Updates**: Livewire-powered dynamic interface

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Livewire + Blade templates
- **Styling**: TailwindCSS
- **Database**: SQLite (configurable)
- **Authentication**: Laravel's built-in auth system

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd teman_ecommerce
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Create SQLite database (or configure your preferred database)
   touch database/database.sqlite
   
   # Run migrations
   php artisan migrate
   
   # Seed with sample data
   php artisan db:seed
   ```

5. **Storage setup**
   ```bash
   php artisan storage:link
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## Usage

### Admin Access
- **URL**: `/admin/products`
- **Login**: `admin@example.com` / `password`
- **Features**:
  - Manage products and variants
  - View and update orders
  - Monitor stock levels

### Customer Store
- **URL**: `/` (root)
- **Features**:
  - Browse products
  - Select variants
  - Add to cart
  - Place orders

## Database Schema

The system uses the following main tables:
- `users` - User accounts
- `products` - Product information
- `product_variants` - Product variants with stock
- `orders` - Customer orders
- `order_items` - Individual items in orders

## Sample Data

The seeder creates:
- Admin user: `admin@example.com` / `password`
- 4 sample products with variants
- Various stock levels for testing

## Customization

### Adding New Product Types
1. Extend the Product model
2. Add new fields to the products table
3. Update the admin forms
4. Modify the store display

### Styling
- Uses TailwindCSS for styling
- Customize colors and layout in the Blade templates
- Modify the Livewire component styles

### Business Logic
- Order processing in `OrderController`
- Cart management in `StorePage` Livewire component
- Stock management in product variants

## Security Features

- CSRF protection on all forms
- Authentication middleware for admin routes
- Input validation and sanitization
- Secure file upload handling

## Development

### Running Tests
```bash
php artisan test
```

### Code Quality
```bash
./vendor/bin/pint
```

### Livewire Development
- Components are in `app/Livewire/`
- Views are in `resources/views/livewire/`
- Use `wire:` directives for real-time updates

## Deployment

1. Set production environment variables
2. Run `php artisan config:cache`
3. Set up proper database credentials
4. Configure web server (Apache/Nginx)
5. Set up SSL certificates
6. Configure file storage (S3, etc.)

## Support

For issues and questions:
1. Check the Laravel documentation
2. Review Livewire documentation
3. Check the code comments and structure

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
