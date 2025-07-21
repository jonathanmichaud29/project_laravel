# Essential Laravel Artisan Commands for Your Hello World Project

## 🚀 Getting Started Commands

### Check Artisan Available Commands

```bash
./vendor/bin/sail artisan list
./vendor/bin/sail artisan help [command]  # Get help for specific command
```

## 🎯 Core Commands for Your Hello World Project

### 1. Controllers

```bash
# Create a basic controller
./vendor/bin/sail artisan make:controller HomeController

# Create a controller with resource methods (index, create, store, show, edit, update, destroy)
./vendor/bin/sail artisan make:controller PageController --resource

# Create a controller with specific methods
./vendor/bin/sail artisan make:controller WelcomeController --model=User
```

### 2. Routes Management

```bash
# List all registered routes
./vendor/bin/sail artisan route:list

# List routes with specific filters
./vendor/bin/sail artisan route:list --name=home
./vendor/bin/sail artisan route:list --method=GET

# Cache routes for production (speeds up routing)
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan route:clear  # Clear route cache
```

### 3. Views & Components (for Blade parts)

```bash
# Create a Blade component (useful for layouts)
./vendor/bin/sail artisan make:component Navigation
./vendor/bin/sail artisan make:component Layout/Header

# Create a view composer (for sharing data across views)
./vendor/bin/sail artisan make:provider ViewServiceProvider
```

## 🗄️ Database Commands (for future use)

### Models

```bash
# Create a model
./vendor/bin/sail artisan make:model Post

# Create model with migration
./vendor/bin/sail artisan make:model Post -m

# Create model with migration, factory, and seeder
./vendor/bin/sail artisan make:model Post -mfs

# Create model with everything (migration, factory, seeder, policy, controller)
./vendor/bin/sail artisan make:model Post --all
```

### Migrations

```bash
# Create a migration
./vendor/bin/sail artisan make:migration create_posts_table
./vendor/bin/sail artisan make:migration add_slug_to_posts_table --table=posts

# Run migrations
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:status
./vendor/bin/sail artisan migrate:rollback
./vendor/bin/sail artisan migrate:fresh  # Drop all tables and re-run migrations
```

### Seeders

```bash
# Apply all seeds
./vendor/bin/sail artisan db:seed
# Apply a specific seed
./vendor/bin/sail artisan db:seed --class=HelloSeeder
```

## 🔧 Development Helper Commands

### Cache Management

```bash
# Clear all caches
./vendor/bin/sail artisan optimize:clear

# Individual cache commands
./vendor/bin/sail artisan config:clear    # Clear config cache
./vendor/bin/sail artisan view:clear      # Clear compiled view files
./vendor/bin/sail artisan route:clear     # Clear route cache
./vendor/bin/sail artisan cache:clear     # Clear application cache

# Cache for production
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```

### Application Management

```bash
# Generate application key
./vendor/bin/sail artisan key:generate

# Put application in maintenance mode
./vendor/bin/sail artisan down
./vendor/bin/sail artisan up

# Serve application (if not using Sail)
php artisan serve
```

## 🎨 Frontend Integration Commands

### Inertia.js Specific

```bash
# Install Inertia middleware (already done in your setup)
./vendor/bin/sail artisan inertia:middleware

# Publish Inertia config
./vendor/bin/sail artisan vendor:publish --provider="Inertia\ServiceProvider"
```

## 🧪 Testing Commands

```bash
# Create a test
./vendor/bin/sail artisan make:test HomePageTest           # Feature test
./vendor/bin/sail artisan make:test UserTest --unit       # Unit test

# Run tests
./vendor/bin/sail artisan test
./vendor/bin/sail artisan test --filter=HomePageTest
```

## 📋 For Your Hello World Project - Step by Step

### 1. Create Controllers for Different Pages

```bash
# Main pages controller
./vendor/bin/sail artisan make:controller PagesController

# Or separate controllers for organization
./vendor/bin/sail artisan make:controller HomeController
./vendor/bin/sail artisan make:controller AboutController
./vendor/bin/sail artisan make:controller ContactController
```

### 2. Check Your Routes

```bash
# See all routes after you define them
./vendor/bin/sail artisan route:list
```

### 3. Clear Caches During Development

```bash
# When you make config changes
./vendor/bin/sail artisan config:clear

# When you add new routes
./vendor/bin/sail artisan route:clear
```

## 💡 Pro Tips

### Helpful Flags

- `--help` - Get help for any command
- `--force` - Force overwrite existing files
- `--resource` - Create resource controller with CRUD methods
- `-m` - Create migration with model
- `-f` - Create factory with model
- `-s` - Create seeder with model

### Quick Reference

```bash
# Most used during development
./vendor/bin/sail artisan make:controller ControllerName
./vendor/bin/sail artisan route:list
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan migrate

# Debugging helpers
./vendor/bin/sail artisan tinker          # Interactive PHP shell
./vendor/bin/sail artisan inspire         # Random inspiring quote
```

## 🎯 Next Steps for Your Hello World Project

1. **Create a PagesController**: `./vendor/bin/sail artisan make:controller PagesController`
2. **Define routes** in `routes/web.php` pointing to your controller methods
3. **Create React components** in `resources/js/Pages/`
4. **Use `route:list`** to verify your routes are registered correctly
5. **Clear config cache** when you make changes: `./vendor/bin/sail artisan config:clear`

Remember: With your Inertia.js + React setup, you'll be creating React components in `resources/js/Pages/` rather than traditional Blade views. Your controllers will return `Inertia::render('ComponentName', $data)` instead of `view()` calls.
