# Laravel Directory Structure - Complete Guide

Here's a breakdown of every folder and key files.

## 📁 Root Directory Overview

```
my-laravel-app/
├── app/                    # Core application code
├── bootstrap/              # Application bootstrap files
├── config/                 # Configuration files
├── database/               # Database migrations, seeds, factories
├── public/                 # Web server document root
├── resources/              # Views, assets, language files
├── routes/                 # Route definitions
├── storage/                # Generated files, logs, cache
├── tests/                  # Automated tests
├── vendor/                 # Composer dependencies
├── .env                    # Environment configuration
├── .env.example           # Environment template
├── artisan                # Command-line interface
├── composer.json          # PHP dependencies
├── composer.lock          # Dependency lock file
├── package.json           # Node.js dependencies
├── phpunit.xml            # Testing configuration
├── README.md              # Project documentation
└── vite.config.js         # Asset bundling configuration
```

---

## 🏗️ Core Directories Explained

### 1. `app/` - The Heart of Your Application

This is where your main application logic lives. It follows the [PSR-4](psr-4.md) autoloading standard.

```
app/
├── Console/               # Artisan commands
│   ├── Commands/         # Custom commands
│   └── Kernel.php        # Command scheduler
├── Exceptions/           # Exception handlers
│   └── Handler.php       # Global exception handling
├── Http/                 # HTTP layer
│   ├── Controllers/      # Request handlers
│   ├── Middleware/       # HTTP middleware
│   ├── Requests/         # Form request validation
│   └── Kernel.php        # HTTP kernel configuration
├── Models/               # Eloquent models
│   └── User.php          # Example user model
├── Providers/            # Service providers
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── EventServiceProvider.php
│   └── RouteServiceProvider.php
├── Events/               # Event classes
├── Jobs/                 # Queueable jobs
├── Listeners/            # Event listeners
├── Mail/                 # Mailable classes
├── Notifications/        # Notification classes
├── Policies/             # Authorization policies
└── Rules/                # Custom validation rules
```

**Key Points:**

- **Models/**: Your database models (User, Post, etc.)
- **Controllers/**: Handle HTTP requests and return responses
- **Middleware/**: Filter HTTP requests entering your application
- **Providers/**: Bootstrap services and bind them to the service container

### 2. `bootstrap/` - Application Bootstrap

```
bootstrap/
├── app.php               # Creates Laravel application instance
├── cache/                # Framework bootstrap cache
│   ├── packages.php      # Package manifest cache
│   └── services.php      # Service provider cache
└── providers.php         # Service provider configuration
```

**Purpose**: Contains files that bootstrap the framework and configure autoloading.

### 3. `config/` - Configuration Files

```
config/
├── app.php               # Core application settings
├── auth.php              # Authentication configuration
├── cache.php             # Cache configuration
├── database.php          # Database connections
├── filesystems.php       # File storage configuration
├── logging.php           # Logging configuration
├── mail.php              # Email configuration
├── queue.php             # Queue configuration
├── services.php          # Third-party services
└── session.php           # Session configuration
```

**Key Files:**

- **app.php**: App name, debug mode, timezone, locale
- **database.php**: Database connections and settings
- **auth.php**: Authentication guards, providers, passwords

### 4. `database/` - Database Related Files

```
database/
├── factories/            # Model factories for testing
│   └── UserFactory.php   # User factory example
├── migrations/           # Database migrations
│   ├── 2024_01_01_000000_create_users_table.php
│   └── 2024_01_01_000001_create_posts_table.php
├── seeders/              # Database seeders
│   ├── DatabaseSeeder.php
│   └── UserSeeder.php
└── .gitignore
```

**Purpose:**

- **Migrations**: Version control for your database schema
- **Seeders**: Populate database with test/default data
- **Factories**: Generate fake data for testing

### 5. `public/` - Web Server Document Root

```
public/
├── build/                # Built assets (after npm run build)
│   ├── assets/          # CSS, JS, images
│   └── manifest.json    # Asset manifest
├── storage/             # Symlinked storage files
├── favicon.ico          # Website favicon
├── index.php            # Entry point for all requests
├── robots.txt           # Search engine instructions
└── .htaccess           # Apache configuration
```

**Critical Notes:**

- **index.php**: Single entry point for all HTTP requests
- **Only this directory** should be accessible by web server
- **Assets**: CSS, JS, images are compiled here by Vite

### 6. `resources/` - Views, Assets, Language Files

```
resources/
├── css/                  # CSS source files
│   └── app.css          # Main stylesheet
├── js/                   # JavaScript source files
│   ├── app.jsx          # React entry point
│   ├── Components/      # React components
│   └── Pages/           # Inertia.js pages
├── lang/                # Language files
│   └── en/              # English translations
├── views/               # Blade templates
│   ├── layouts/         # Layout templates
│   ├── components/      # Blade components
│   └── app.blade.php    # Main layout
└── markdown/            # Markdown files
```

**For Your Inertia.js Setup:**

- **js/Pages/**: React components for each page
- **js/Components/**: Reusable React components
- **css/app.css**: Tailwind CSS entry point

### 7. `routes/` - Route Definitions

```
routes/
├── api.php              # API routes (/api/* prefix)
├── channels.php         # Broadcast channels
├── console.php          # Artisan commands
└── web.php              # Web routes (session, CSRF, cookies)
```

**Route Files:**

- **web.php**: Standard web routes with sessions
- **api.php**: Stateless API routes
- **channels.php**: WebSocket/broadcasting routes

### 8. `storage/` - Generated Files

```
storage/
├── app/                 # Application files
│   ├── public/         # User-uploaded files
│   └── private/        # Private files
├── framework/          # Framework generated files
│   ├── cache/          # Application cache
│   ├── sessions/       # Session files
│   └── views/          # Compiled Blade templates
└── logs/               # Application logs
    └── laravel.log     # Main log file
```

**Important:**

- **Writable**: Must be writable by web server
- **Logs**: Check `storage/logs/laravel.log` for debugging
- **Cache**: Framework caches compiled views and routes here

### 9. `tests/` - Automated Tests

```
tests/
├── Feature/            # Feature tests (HTTP, database)
│   └── ExampleTest.php
├── Unit/               # Unit tests (isolated components)
│   └── ExampleTest.php
├── CreatesApplication.php
└── TestCase.php
```

**Test Types:**

- **Feature**: Test complete features (login, registration)
- **Unit**: Test individual classes/methods

### 10. `vendor/` - Composer Dependencies

```
vendor/                 # Third-party packages
├── laravel/           # Laravel framework
├── doctrine/          # Database abstraction
├── symfony/           # Symfony components
└── autoload.php       # Composer autoloader
```

**Note**: Never modify files in `vendor/` - they're overwritten during updates.

---

## 🔧 Key Root Files

### `.env` - Environment Configuration

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

**Purpose**: Environment-specific settings (database, mail, cache)

### `artisan` - Command Line Interface

```bash
# Examples of artisan commands
php artisan migrate              # Run migrations
php artisan make:controller UserController
php artisan serve               # Start development server
php artisan tinker              # Interactive shell
```

### `composer.json` - PHP Dependencies

```json
{
  "require": {
    "php": "^8.1",
    "laravel/framework": "^10.0",
    "inertiajs/inertia-laravel": "^0.6"
  },
  "require-dev": {
    "phpunit/phpunit": "^10.0"
  }
}
```

### `package.json` - Node.js Dependencies

```json
{
  "devDependencies": {
    "@vitejs/plugin-react": "^4.0.0",
    "vite": "^4.0.0",
    "tailwindcss": "^3.0.0"
  }
}
```

---

## 🎯 How It All Works Together

### 1. Request Flow Through Directories

```
1. public/index.php          # Entry point
2. bootstrap/app.php         # Bootstrap Laravel
3. config/*                  # Load configuration
4. routes/web.php            # Route matching
5. app/Http/Controllers/     # Handle request
6. app/Models/               # Data access
7. resources/views/          # Render response
8. public/                   # Serve response
```

### 2. Development Workflow

```bash
# 1. Make changes in source directories
resources/js/Pages/Welcome.jsx    # Edit React component
app/Http/Controllers/           # Edit controller
database/migrations/            # Create migration

# 2. Run development commands
npm run dev                     # Compile assets
php artisan migrate            # Update database
php artisan serve              # Start server
```

### 3. File Organization Best Practices

**Controllers**: Group related actions

```
app/Http/Controllers/
├── Admin/
│   ├── UserController.php
│   └── PostController.php
├── API/
│   └── UserController.php
└── UserController.php
```

**Models**: Organize by feature

```
app/Models/
├── User.php
├── Post.php
├── Category.php
└── Blog/
    ├── Post.php
    └── Comment.php
```

**Views/Components**: Mirror URL structure

```
resources/js/Pages/
├── Auth/
│   ├── Login.jsx
│   └── Register.jsx
├── Dashboard.jsx
└── Profile/
    ├── Show.jsx
    └── Edit.jsx
```

---

## 🚨 Common Pitfalls & Tips

### ❌ What NOT to Do

1. **Don't modify `vendor/`** - Changes are lost on updates
2. **Don't put logic in routes** - Use controllers instead
3. **Don't store files in `public/`** - Use `storage/` with symlinks
4. **Don't hardcode paths** - Use Laravel helpers

### ✅ Best Practices

1. **Use Laravel helpers**:

   ```php
   // Good
   storage_path('app/uploads')
   base_path('resources/views')

   // Bad
   '/var/www/html/storage/app/uploads'
   ```

2. **Follow PSR-4 conventions**:

   ```php
   // File: app/Http/Controllers/UserController.php
   namespace App\Http\Controllers;

   class UserController extends Controller
   ```

3. **Organize by feature**:
   ```
   app/
   ├── Http/Controllers/Blog/
   ├── Models/Blog/
   └── Services/Blog/
   ```

### 🔍 Debugging Directory Issues

```bash
# Check permissions
ls -la storage/
ls -la bootstrap/cache/

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Recreate storage link
php artisan storage:link
```

---

## 📝 Summary

Laravel's directory structure is designed to:

1. **Separate concerns** - Models, Views, Controllers in different directories
2. **Follow conventions** - Predictable file locations
3. **Support scaling** - Organized structure for large applications
4. **Enable tooling** - Artisan commands know where to create files

**Key Takeaways:**

- `app/` = Your application code
- `resources/` = Views, assets, frontend code
- `config/` = All configuration
- `database/` = Schema and data management
- `public/` = Web-accessible files only
- `storage/` = Generated files, logs, cache

Understanding this structure helps you navigate Laravel projects efficiently and know exactly where to find or create files for different purposes.
