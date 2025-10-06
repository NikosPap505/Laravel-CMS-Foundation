# Laravel CMS Foundation

A modern, feature-rich Content Management System built with Laravel 12, featuring a stunning public website, comprehensive admin panel, and enterprise-grade security practices.

## ✨ What's New

- **🎨 Modern Public Website**: Stunning homepage with hero sections, interactive features showcase, and professional design
- **📱 Fully Responsive**: Beautiful design that works perfectly on all devices
- **🎭 Interactive Elements**: Smooth animations, hover effects, and engaging user interactions
- **📊 Enhanced Blog**: Modern blog design with filters, search, and improved readability
- **🔍 Advanced Features**: Table of contents, reading progress, social sharing, and more
- **🎯 Professional Pages**: Redesigned About, Contact, and all dynamic pages

## 🚀 Features

### Public Website
- **Modern Homepage**: Hero section with animated counters, features showcase, and testimonials
- **Interactive Blog**: Advanced filtering, search, and modern post design
- **Professional Pages**: About, Contact, Privacy Policy with consistent modern design
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Performance Optimized**: Fast loading with optimized assets and caching

### Content Management
- **Posts & Pages**: Rich text editor with media management
- **Categories & Tags**: Organized content structure
- **Media Library**: Advanced file management with validation
- **SEO Tools**: Meta tags, sitemaps, and SEO-friendly URLs
- **Content Scheduling**: Publish posts at specific times

### User Management
- **Role-Based Access**: Admin, Editor, and custom roles
- **User Permissions**: Granular permission system
- **Profile Management**: User profiles with avatar support
- **Security Features**: Rate limiting, CSRF protection, and secure authentication

### Technical Features
- **Modern Security**: Rate limiting, security headers, and file validation
- **Performance**: Database indexing, caching layer, and query optimization
- **Testing**: Comprehensive test suite with 36+ tests
- **API Ready**: RESTful API endpoints for content management
- **Multi-language Support**: Ready for internationalization

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

## 🌐 Website Features

### Homepage
- **Hero Section**: Animated background with compelling messaging and CTA buttons
- **Features Showcase**: Interactive grid showcasing CMS capabilities
- **Before/After Comparison**: Visual demonstration of CMS benefits
- **Recent Posts**: Latest blog posts with modern card design
- **Trust Indicators**: Animated counters and testimonials
- **Call-to-Action**: Professional conversion-focused sections

### Blog System
- **Modern Design**: Clean, readable blog layout with enhanced typography
- **Advanced Filtering**: Filter by category, sort by date/popularity
- **Search Functionality**: Full-text search across posts
- **Reading Experience**: Table of contents, reading progress, and time estimates
- **Social Sharing**: Built-in sharing buttons for social media
- **Responsive Cards**: Beautiful post cards with hover animations

### Dynamic Pages
- **Professional Design**: Consistent modern design across all pages
- **Enhanced Typography**: Improved readability with custom styling
- **SEO Optimized**: Proper meta tags and structured content
- **Interactive Elements**: Engaging animations and hover effects

## 🎯 Admin Features

- **Modern Dashboard**: Clean, intuitive admin interface
- **Content Management**: Create, edit, schedule, and manage posts/pages
- **Media Library**: Advanced file upload and management with validation
- **User Management**: Role assignment and permission management
- **SEO Tools**: Meta tag management and sitemap generation
- **Settings Panel**: Site configuration and customization options
- **Analytics**: Content performance tracking and insights

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

## 🎨 Design System

### Modern UI Components
- **Glassmorphism Effects**: Backdrop blur and transparency for modern aesthetics
- **Gradient Backgrounds**: Beautiful color transitions and animated elements
- **Interactive Animations**: Smooth hover effects, transitions, and micro-interactions
- **Responsive Grid**: Mobile-first design with Tailwind CSS
- **Typography**: Enhanced readability with custom font sizing and spacing
- **Color Scheme**: Professional color palette with accent colors and proper contrast

### Animation Features
- **Counter Animations**: Animated number counters on scroll
- **Floating Elements**: Subtle background animations for visual interest
- **Hover Effects**: Interactive button and card animations
- **Scroll Animations**: Elements that animate when entering viewport
- **Loading States**: Smooth transitions and loading indicators

## 📁 Project Structure

```
app/
├── Console/Commands/     # Custom Artisan commands
├── Http/
│   ├── Controllers/Admin/ # Admin panel controllers
│   ├── Controllers/      # Public website controllers
│   ├── Middleware/       # Custom middleware
│   └── Requests/         # Form request validation
├── Models/              # Eloquent models
└── helpers.php          # Helper functions

database/
├── factories/           # Model factories for testing
├── migrations/          # Database migrations
└── seeders/            # Database seeders

resources/
├── views/
│   ├── admin/          # Admin panel views
│   ├── layouts/        # Layout templates
│   ├── components/     # Reusable components
│   ├── blog/          # Blog-specific views
│   ├── contact/       # Contact form views
│   └── page/          # Dynamic page views
├── css/               # Custom styles and animations
└── js/                # Frontend JavaScript

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

## 🌐 Website Pages

### Public Pages
- **Homepage** (`/`): Modern hero section, features showcase, and recent posts
- **Features** (`/features`): Detailed CMS capabilities and benefits
- **Blog** (`/blog`): Modern blog listing with filters and search
- **Blog Post** (`/blog/{slug}`): Individual posts with enhanced reading experience
- **Blog Category** (`/blog/category/{category}`): Category-specific blog listings
- **About Us** (`/about-us`): Professional company information
- **Contact** (`/contact`): Modern contact form with validation
- **Privacy Policy** (`/privacy-policy`): Professional policy pages
- **Dynamic Pages** (`/{slug}`): Any custom pages created through the CMS

### Admin Pages
- **Dashboard** (`/dashboard`): Content overview and management
- **Posts Management** (`/admin/posts`): Create, edit, and manage blog posts
- **Pages Management** (`/admin/pages`): Static page management
- **Media Library** (`/admin/media`): File upload and management
- **User Management** (`/admin/users`): User roles and permissions
- **Settings** (`/admin/settings`): Site configuration

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

4. **Run migrations and seeders**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Create admin user**
   ```bash
   php artisan cms:assign-admin your-email@example.com
   ```

6. **Optimize for production**
   ```bash
   php artisan optimize
   php artisan cms:clear-cache
   ```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🆕 Recent Updates

### Version 2.0 - Modern Website Redesign
- ✅ **Complete Homepage Redesign**: Modern hero section with animations and interactive elements
- ✅ **Enhanced Blog System**: Advanced filtering, search, and improved reading experience
- ✅ **Professional Page Design**: Consistent modern design across all dynamic pages
- ✅ **Interactive Features**: Smooth animations, hover effects, and engaging user interactions
- ✅ **Mobile-First Design**: Fully responsive design that works perfectly on all devices
- ✅ **Performance Optimizations**: Faster loading times and improved user experience
- ✅ **Enhanced Typography**: Better readability with custom styling and improved contrast
- ✅ **Social Features**: Built-in social sharing and reading progress indicators

### Key Improvements
- **Design Consistency**: All pages now follow the same modern design language
- **User Experience**: Improved navigation, readability, and interactive elements
- **Performance**: Optimized assets, caching, and faster page load times
- **Accessibility**: Better contrast, typography, and responsive design
- **SEO**: Enhanced meta tags, structured content, and search optimization

## 📞 Support

For support, please open an issue in the GitHub repository or contact us through the website.

## 🎯 Demo

Visit the live demo to see all the modern features in action:
- **Homepage**: Experience the stunning hero section and interactive elements
- **Blog**: Try the advanced filtering and search functionality
- **Pages**: See the consistent modern design across all pages
- **Admin Panel**: Explore the comprehensive content management features

---

Built with ❤️ using Laravel and modern web technologies
