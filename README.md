# Laravel + Inertia.js + React Project

A modern full-stack web application built with Laravel backend and React frontend, seamlessly connected through Inertia.js.

## 🚀 Tech Stack

### Backend

- **Laravel 11** - PHP web framework
- **MySQL** - Primary database
- **Redis** - Caching and session storage
- **Laravel Sail** - Docker-based development environment

### Frontend

- **React 18** - JavaScript library for building user interfaces
- **Inertia.js** - Modern monolith approach (no API needed)
- **TypeScript** - Type-safe JavaScript (optional)
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Fast build tool and dev server

### Development Tools

- **Docker & Docker Compose** - Containerized development
- **Node.js 18+** - JavaScript runtime
- **Composer** - PHP dependency manager
- **Hot Module Replacement** - Instant code updates

## 📋 Prerequisites

- Docker & Docker Compose
- Node.js 18+ and npm/yarn
- Composer (PHP dependency manager)

## 🛠️ Installation

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd <project-name>
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Start Docker environment**

   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Install JavaScript dependencies**

   ```bash
   ./vendor/bin/sail npm install
   ```

5. **Set up environment**

   ```bash
   cp .env.example .env
   ./vendor/bin/sail artisan key:generate
   ```

6. **Run database migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

## 🏃‍♂️ Development Workflow

### Start Development Environment

```bash
# Start Docker containers (Laravel, MySQL, Redis)
./vendor/bin/sail up -d

# Start Vite development server (in a new terminal)
./vendor/bin/sail npm run dev
```

Your application will be available at:

- **Frontend**: http://localhost
- **Vite Dev Server**: http://localhost:5173
- **Database**: localhost:3306

### Stop Development Environment

```bash
# Stop Vite dev server
# Press Ctrl+C in the terminal running npm run dev

# Stop Docker containers
./vendor/bin/sail down
```

### Alternative Commands

```bash
# Start containers in foreground (see logs)
./vendor/bin/sail up

# Start only specific services
./vendor/bin/sail up -d mysql redis

# View logs
./vendor/bin/sail logs

# Stop and remove volumes (complete reset)
./vendor/bin/sail down -v
```

## 🔧 Common Development Commands

### Laravel Commands

```bash
# Run Artisan commands
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan make:controller UserController
./vendor/bin/sail artisan make:model Post -m

# Clear caches
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear
```

### Frontend Commands

```bash
# Install new npm packages
./vendor/bin/sail npm install <package-name>

# Build for production
./vendor/bin/sail npm run build

# Run tests
./vendor/bin/sail npm test
```

### Database Commands

```bash
# Run migrations
./vendor/bin/sail artisan migrate

# Rollback migrations
./vendor/bin/sail artisan migrate:rollback

# Fresh migration with seeders
./vendor/bin/sail artisan migrate:fresh --seed

# Access MySQL directly
./vendor/bin/sail mysql
```

### Container Management

```bash
# Access container shell
./vendor/bin/sail shell

# Execute commands in container
./vendor/bin/sail exec laravel.test php --version

# View running containers
docker ps
```

## 🗂️ Project Structure

```
├── app/                          # Laravel application code
│   ├── Http/Controllers/         # Controllers
│   ├── Models/                   # Eloquent models
│   └── Http/Middleware/          # Custom middleware
├── resources/
│   ├── js/
│   │   ├── Pages/               # React page components
│   │   ├── Components/          # Reusable React components
│   │   └── app.jsx              # React entry point
│   ├── css/
│   │   └── app.css              # Tailwind CSS
│   └── views/
│       └── app.blade.php        # Root HTML template
├── routes/
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── docker-compose.yml           # Sail Docker configuration
├── vite.config.js              # Vite configuration
├── tailwind.config.js          # Tailwind CSS configuration
└── package.json                # Frontend dependencies
```

## 🌐 Environment Configuration

### Database Connection

- **Host**: localhost
- **Port**: 3306
- **Database**: laravel
- **Username**: sail
- **Password**: password

### Redis Connection

- **Host**: localhost
- **Port**: 6379

### Environment Variables (.env)

```env
APP_NAME="My Laravel App"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=***
DB_DATABASE=***
DB_USERNAME=***
DB_PASSWORD=***

REDIS_HOST=redis
REDIS_PASSWORD=***
REDIS_PORT=***

VITE_APP_NAME="${APP_NAME}"
```

## 🚀 Deployment

### Build for Production

```bash
# Build optimized assets
./vendor/bin/sail npm run build

# Optimize Laravel
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```

## 🔍 Troubleshooting

### Common Issues

1. **Permission Errors**

   ```bash
   sudo chown -R $USER:$USER .
   ```

2. **Port Already in Use**

   ```bash
   ./vendor/bin/sail down
   docker ps
   # Kill any conflicting containers
   ```

3. **npm Install Errors**

   ```bash
   ./vendor/bin/sail npm cache clean --force
   rm -rf node_modules package-lock.json
   ./vendor/bin/sail npm install
   ```

4. **Database Connection Issues**
   ```bash
   ./vendor/bin/sail artisan config:clear
   ./vendor/bin/sail up -d mysql
   ```

### Logs

```bash
# Laravel logs
./vendor/bin/sail logs

# Specific service logs
./vendor/bin/sail logs mysql

# Application logs
tail -f storage/logs/laravel.log
```

## 📚 Key Features

- **No API Required**: Direct controller-to-React component data flow
- **Hot Reload**: Instant updates during development
- **SEO Friendly**: Server-side rendering capabilities
- **Type Safety**: TypeScript support for better development experience
- **Modern Styling**: Tailwind CSS for rapid UI development
- **Docker Ready**: Complete containerized development environment

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

---

**Happy coding!** 🎉
