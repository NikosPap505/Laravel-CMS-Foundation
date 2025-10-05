# Laravel CMS Foundation

A robust, feature-rich Content Management System built with Laravel 12, featuring modern security practices, comprehensive testing, and performance optimizations.

## 🚀 Features

- **Content Management**: Posts, Pages, Categories, and Media management
- **User Management**: Role-based access control with admin/editor roles
- **Security**: Rate limiting, security headers, and enhanced file validation
- **Performance**: Database indexing, caching layer, and query optimization
- **Testing**: Comprehensive test suite with 36+ tests
- **Modern UI**: Clean, responsive admin interface with Tailwind CSS
- **SEO Ready**: Meta tags, sitemaps, and SEO-friendly URLs

## 📋 Requirements

- PHP 8.2+
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js & NPM (for frontend assets)

## 🛠 Installation

1. **Clone the repository**
   ```bash
   git clone (https://github.com/NikosPap505/Laravel-CMS-Foundation.git)
   cd Laravel-CMS-Foundation
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
   # Create your database and update .env with credentials
   php artisan migrate
   php artisan db:seed
   ```

5. **Create admin user**
   ```bash
   php artisan cms:assign-admin your-email@example.com
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

## 🧪 Testing Setup

1. **Copy the testing configuration**
   ```bash
   cp phpunit.xml.example phpunit.xml
   ```

2. **Update phpunit.xml with your test database credentials**

3. **Run tests**
   ```bash
   php artisan test
   ```

## 🔐 Security Features

- Rate limiting on forms and uploads
- Security headers (XSS protection, content type options)
- File upload validation with MIME type checking
- Content Security Policy
- Soft deletes for data recovery
- Role-based access control

## 📊 Performance Features

- Database indexes for optimal query performance
- Smart caching for frequently accessed content
- Automatic cache invalidation
- Optimized database queries with eager loading

## 🎯 Admin Features

- **Dashboard**: Content overview and management
- **Posts**: Create, edit, schedule, and manage blog posts
- **Pages**: Static page management with SEO fields
- **Media**: File upload and management with validation
- **Users**: User management with role assignment
- **Settings**: Site configuration and customization

## 🛠 Available Commands

```bash
# Create admin user
php artisan cms:assign-admin email@example.com

# Clear CMS cache
php artisan cms:clear-cache

# Run tests
php artisan test

# Seed admin user
php artisan db:seed --class=AdminUserSeeder
```

## 📁 Project Structure

```
app/
├── Console/Commands/     # Custom Artisan commands
├── Http/
│   ├── Controllers/Admin/ # Admin panel controllers
│   ├── Middleware/       # Custom middleware
│   └── Requests/         # Form request validation
├── Models/              # Eloquent models
└── helpers.php          # Helper functions

database/
├── factories/           # Model factories for testing
├── migrations/          # Database migrations
└── seeders/            # Database seeders

resources/views/
├── admin/              # Admin panel views
├── layouts/            # Layout templates
└── components/         # Reusable components

tests/
├── Feature/            # Feature tests
└── Unit/              # Unit tests
```

## 🔧 Configuration

### Environment Variables

Key environment variables to configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### Cache Configuration

The CMS uses Laravel's caching system. Configure your preferred cache driver in `.env`:

```env
CACHE_DRIVER=redis  # or file, database, etc.
```

## 🚀 Deployment

1. **Production environment setup**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Set up your web server** (Apache/Nginx) to point to the `public/` directory

3. **Configure your database** and update `.env` with production credentials

4. **Run migrations**
   ```bash
   php artisan migrate --force
   ```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support, please open an issue in the GitHub repository.

---

Built with ❤️ using Laravel
