#!/bin/bash

# Gear-In E-Commerce Setup Script
# This script automates the setup process for the application

set -e

echo "🚀 Gear-In E-Commerce Setup Script"
echo "===================================="
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo "✅ PHP version: $PHP_VERSION"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer."
    exit 1
fi

echo "✅ Composer is installed"

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js 18.x or higher."
    exit 1
fi

NODE_VERSION=$(node -v)
echo "✅ Node.js version: $NODE_VERSION"

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ npm is not installed. Please install npm."
    exit 1
fi

echo "✅ npm is installed"
echo ""

# Step 1: Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction
echo "✅ PHP dependencies installed"
echo ""

# Step 2: Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install
echo "✅ Node.js dependencies installed"
echo ""

# Step 3: Setup environment
if [ ! -f .env ]; then
    echo "⚙️  Setting up environment file..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ Environment file created"
else
    echo "⚠️  .env file already exists, skipping..."
fi
echo ""

# Step 4: Create database file
if [ ! -f database/database.sqlite ]; then
    echo "🗄️  Creating database file..."
    touch database/database.sqlite
    echo "✅ Database file created"
else
    echo "⚠️  Database file already exists, skipping..."
fi
echo ""

# Step 5: Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed"
echo ""

# Step 6: Seed database
echo "🌱 Seeding database..."
php artisan db:seed --force
echo "✅ Database seeded"
echo ""

# Step 7: Build assets
echo "🎨 Building frontend assets..."
npm run build
echo "✅ Assets built"
echo ""

# Step 8: Clear caches
echo "🧹 Clearing caches..."
php artisan optimize:clear
echo "✅ Caches cleared"
echo ""

echo "===================================="
echo "✅ Setup completed successfully!"
echo ""
echo "📝 Default Login Accounts:"
echo "   Admin:    admin@gear-in.dev / password"
echo "   Customer: customer@gear-in.dev / password"
echo ""
echo "🚀 To start the server, run:"
echo "   php artisan serve"
echo ""
echo "🌐 Then open: http://127.0.0.1:8000"
echo ""

