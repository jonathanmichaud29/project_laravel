# Laravel + Inertia.js + React Setup Guide

## Prerequisites

Ensure you have the following installed:

- **Docker & Docker Compose** (you're already familiar with this)
- **Node.js 18+** and **npm/yarn**
- **Composer** (PHP dependency manager)

## 1. Project Initialization

### Create New Laravel Project

```bash
# Create Laravel project with Sail (Docker environment)
curl -s "https://laravel.build/my-app?with=mysql,redis" | bash

# Navigate to project
cd my-app

# Start Docker containers
./vendor/bin/sail up -d
```

## 2. Install Inertia.js Server-Side

### Install Inertia Laravel Adapter

```bash
# Using Sail (recommended)
./vendor/bin/sail composer require inertiajs/inertia-laravel
```

### Publish Inertia Middleware

```bash
./vendor/bin/sail artisan inertia:middleware
```

Edit `bootstrap/app.php` :

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that's loaded on the first page visit.
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   */
  public function version(Request $request): string|null
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   */
  public function share(Request $request): array
  {
    return array_merge(parent::share($request), [
      // Always available in every component
      'auth' => [
        'user' => $request->user(),
        'permissions' => $request->user()?->permissions ?? [],
      ],

      // Flash messages from redirects
      'flash' => [
        'message' => fn () => $request->session()->get('message'),
        'error' => fn () => $request->session()->get('error'),
        'success' => fn () => $request->session()->get('success'),
      ],

      // App-wide settings
      'app' => [
        'name' => config('app.name'),
        'locale' => app()->getLocale(),
      ],

      // CSRF token for forms
      'csrf_token' => csrf_token(),
    ]);
  }
}
```

### Create Root Template

Create `resources/views/app.blade.php`:

```html
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0"
    />
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/js/app.jsx',
    "resources/js/Pages/{$page['component']}.jsx"]) @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html>
```

## 3. Install Frontend Dependencies

### Install React and Inertia Client-Side

**Step 1: Clean Setup**

```bash
# If you have installation errors, clean up first
./vendor/bin/sail npm cache clean --force
./vendor/bin/sail exec laravel.test rm -rf node_modules
./vendor/bin/sail exec laravel.test rm -f package-lock.json
```

**Step 2: Install React and Inertia Client-Side**

```bash
# Install React and Inertia.js client (run separately)
./vendor/bin/sail npm install @inertiajs/react react react-dom

# Install development dependencies
./vendor/bin/sail npm install -D @vitejs/plugin-react
```

**Step 3: Install TypeScript Support**

```bash
./vendor/bin/sail npm install -D @types/react @types/react-dom typescript
```

**Step 4: Install Tailwind CSS**

```bash
./vendor/bin/sail npm install -D tailwindcss postcss autoprefixer
```

**Step 5: Create `my-app/tailwind.config.js`**

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.jsx",
    "./resources/**/*.ts",
    "./resources/**/*.tsx",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
```

**Step 6: Create `my-app/postcss.config.js`**

```javascript
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
};
```

## 4. Configure Vite

### Update `my-app/vite.config.js`

```javascript
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";

export default defineConfig({
  plugins: [
    laravel({
      input: "resources/js/app.jsx",
      refresh: true,
    }),
    react(),
  ],
  server: {
    host: "0.0.0.0", // Important for Docker
    port: 5173,
    hmr: {
      host: "localhost",
    },
  },
});
```

### Configure Tailwind CSS

Update `my-app/tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.jsx",
    "./resources/**/*.ts",
    "./resources/**/*.tsx",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
```

## 5. Setup React Entry Point

### Create `resources/js/app.jsx`

```jsx
import "./bootstrap";
import "../css/app.css";

import { createRoot } from "react-dom/client";
import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.jsx`,
      import.meta.glob("./Pages/**/*.jsx")
    ),
  setup({ el, App, props }) {
    const root = createRoot(el);
    root.render(<App {...props} />);
  },
  progress: {
    color: "#4B5563",
  },
});
```

### Update `resources/css/app.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

## 6. Create Your First Inertia Page

### Create React Component

Create `resources/js/Pages/Welcome.jsx`:

```jsx
import { Head } from "@inertiajs/react";

export default function Welcome({ user }) {
  return (
    <>
      <Head title="Welcome" />
      <div className="min-h-screen bg-gray-100 flex items-center justify-center">
        <div className="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
          <h1 className="text-2xl font-bold text-gray-800 mb-4">
            Welcome to Laravel + Inertia.js + React!
          </h1>
          <p className="text-gray-600 mb-4">
            Your modern full-stack application is ready.
          </p>
          {user ? (
            <p className="text-green-600">Hello, {user.name}!</p>
          ) : (
            <p className="text-blue-600">Please log in to continue.</p>
          )}
        </div>
      </div>
    </>
  );
}
```

### Create Laravel Route

Update `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'user' => auth()->user()
    ]);
});
```

## 7. Development Workflow

### Start Development Servers

```bash
# Terminal 1: Start Laravel/Docker
./vendor/bin/sail up -d

# Terminal 2: Start Vite dev server
./vendor/bin/sail npm run dev
```

### Build for Production

```bash
# Build assets
./vendor/bin/sail npm run build

# Deploy with optimized assets
```

## 8. Additional Configuration

### Environment Variables

Add to `.env`:

```env
APP_NAME="My Laravel App"
VITE_APP_NAME="${APP_NAME}"
```

### Database Configuration

Laravel Sail comes pre-configured with MySQL. Access via:

- **Host:** localhost
- **Port:** 3306
- **Database:** my_app
- **Username:** sail
- **Password:** password

### Redis Configuration

Redis is available at `localhost:6379` for caching and sessions.

## 9. Useful Commands

### Laravel Sail Commands

```bash
# Run artisan commands
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan make:controller UserController

# Run npm commands
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# Access container shell
./vendor/bin/sail shell
```

### Create Inertia CRUD Example

```bash
# Create controller
./vendor/bin/sail artisan make:controller PostController --resource

# Create migration
./vendor/bin/sail artisan make:migration create_posts_table

# Create model
./vendor/bin/sail artisan make:model Post
```

## 10. Project Structure

```
my-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/HandleInertiaRequests.php
├── resources/
│   ├── js/
│   │   ├── Pages/          # React components
│   │   ├── Components/     # Reusable components
│   │   └── app.jsx         # Entry point
│   ├── css/
│   │   └── app.css         # Tailwind CSS
│   └── views/
│       └── app.blade.php   # Root template
├── routes/
│   └── web.php             # Define your routes
└── docker-compose.yml      # Sail configuration
```

## 11. Next Steps

1. **Authentication:** Install Laravel Breeze with Inertia

   ```bash
   ./vendor/bin/sail composer require laravel/breeze --dev
   ./vendor/bin/sail artisan breeze:install react
   ```

2. **State Management:** Consider Zustand or React Query for complex state
3. **Form Handling:** Use Inertia's form helpers for seamless form submissions
4. **API Integration:** Mix Inertia pages with API routes when needed

## Pro Tips

- **Hot Reload:** Vite provides instant hot reload for React components
- **SSR Ready:** Inertia.js can be configured for server-side rendering
- **No API Needed:** Direct controller-to-React component data flow
- **SEO Friendly:** Full server-side rendering capabilities
- **Progressive:** Can gradually migrate from Blade to React components
