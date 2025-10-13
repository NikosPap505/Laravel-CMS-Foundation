#!/bin/bash

# Laravel CMS Foundation Setup Script
# This script helps set up the CMS with proper configuration

echo "🚀 Laravel CMS Foundation Setup"
echo "================================"

# Check if PHP intl extension is installed
echo "Checking PHP intl extension..."
if ! php -m | grep -q intl; then
    echo "❌ PHP intl extension is missing!"
    echo "Please install it with: sudo apt-get install php-intl"
    echo "Then restart your web server and run this script again."
    exit 1
else
    echo "✅ PHP intl extension is installed"
fi

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from example..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "✅ .env file exists"
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate
    echo "✅ Application key generated"
else
    echo "✅ Application key already set"
fi

# Check environment
if grep -q "APP_ENV=production" .env; then
    echo "⚠️  WARNING: Production environment detected!"
    read -p "Are you sure you want to run migrations with --force in production? (yes/no): " -r
    if [[ ! $REPLY =~ ^[Yy][Ee][Ss]$ ]]; then
        echo "❌ Setup cancelled"
        exit 1
    fi
fi

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force
echo "✅ Database migrations completed"

# Create admin user
echo "Creating admin user..."
php artisan cms:assign-admin admin@example.com
echo "✅ Admin user created (admin@example.com)"

# Install npm dependencies
if [ -f package.json ]; then
    echo "Installing npm dependencies..."
    npm install
    echo "✅ NPM dependencies installed"
fi

# Build assets
if [ -f package.json ]; then
    echo "Building frontend assets..."
    npm run build
    echo "✅ Frontend assets built"
fi

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "Next steps:"
echo "1. Update .env file with your database credentials (if using MySQL)"
echo "2. Configure AI API keys in .env if you want AI features"
echo "3. Start the development server: php artisan serve"
echo "4. Visit http://localhost:8000 and login with admin@example.com"
echo ""
echo "Default admin credentials:"
echo "Email: admin@example.com"
echo "Password: change_this_secure_password"
echo ""
echo "⚠️  Remember to change the admin password in production!"
