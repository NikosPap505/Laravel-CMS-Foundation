#!/bin/bash

# Laravel CMS Foundation - Network Access Setup
# This script helps configure the CMS for network access

echo "🌐 Laravel CMS Foundation - Network Access Setup"
echo "================================================"

# Get the local IP address
LOCAL_IP=$(ip route get 1.1.1.1 | awk '{print $7; exit}')
echo "📍 Your local IP address: $LOCAL_IP"

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

# Update APP_URL in .env to use the local IP
echo "Updating APP_URL for network access..."
# Cross-platform sed replacement
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    sed -i '' "s|^APP_URL=.*|APP_URL=http://$LOCAL_IP:8000|g" .env
else
    # Linux and other Unix-like systems
    sed -i "s|^APP_URL=.*|APP_URL=http://$LOCAL_IP:8000|g" .env
fi
echo "✅ APP_URL updated to: http://$LOCAL_IP:8000"

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force
echo "✅ Database migrations completed"

# Create admin user if it doesn't exist
echo "Creating admin user..."
php artisan cms:assign-admin admin@example.com
echo "✅ Admin user created (admin@example.com)"

# Build assets
if [ -f package.json ]; then
    echo "Building frontend assets..."
    npm run build
    echo "✅ Frontend assets built"
fi

echo ""
echo "🎉 Network setup completed successfully!"
echo ""
echo "📱 Access your CMS from other devices:"
echo "   • From this computer: http://localhost:8000"
echo "   • From other devices: http://$LOCAL_IP:8000"
echo ""
# Generate a secure random password
ADMIN_PASSWORD=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-25)

echo "🔐 Default admin credentials:"
echo "   Email: admin@example.com"
echo "   Password: $ADMIN_PASSWORD"
echo ""
echo "⚠️  IMPORTANT: Please change this password immediately after first login!"
echo "   You can do this in the admin panel under Settings > Security"
echo ""
echo "🚀 To start the server for network access:"
echo "   php artisan serve --host=0.0.0.0 --port=8000"
echo ""
echo "⚠️  Security Notes:"
echo "   • This setup is for development/testing only"
echo "   • For production, use a proper web server (Apache/Nginx)"
echo "   • Change the admin password immediately"
echo "   • Consider setting up SSL/HTTPS for production use"
