#!/bin/bash
set -e

echo "🚀 Setup Laravel dalam Codespace..."

# Install PHP extensions
sudo apt-get update
sudo apt-get install -y php8.3-bcmath php8.3-gd unzip curl git

# Install dependencies
composer install
npm install

# Copy env kalau belum ada
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

# Migrate DB
php artisan migrate || true

echo "✅ Setup complete. Run: php artisan serve --host=0.0.0.0 --port=8000"
