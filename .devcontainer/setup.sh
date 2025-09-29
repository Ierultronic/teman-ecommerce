#!/bin/bash
set -e

echo "🚀 Starting Laravel setup..."

# Update & upgrade
sudo apt update && sudo apt upgrade -y

# Install extra PHP extensions untuk Laravel
sudo apt install -y php8.3-bcmath php8.3-gd unzip curl git

# Install project dependencies
composer install
npm install

# Setup .env kalau tak ada lagi
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

# Start PostgreSQL service
sudo service postgresql start

# Buat DB + user untuk Laravel
sudo -u postgres psql -c "CREATE USER laravel WITH PASSWORD 'secret';" || true
sudo -u postgres psql -c "CREATE DATABASE laraveldb OWNER laravel;" || true

# Run migrations
php artisan migrate || true

echo "✅ Laravel setup complete! Jalankan: php artisan serve --host=0.0.0.0 --port=8000"
