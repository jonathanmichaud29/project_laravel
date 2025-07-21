# Laravel Basics - Routing with Inertia

## 1. Basic Routes

```php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;

// GET route - Render React component
Route::get('/users', function () {
  $users = \App\Models\User::all();
  return Inertia::render('Users/Index', [
    'users' => $users
  ]);
});

// POST route - Handle form submission and redirect
Route::post('/users', function (\Illuminate\Http\Request $request) {
  $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users'
  ]);

  \App\Models\User::create($validated);

  return redirect()->route('users.index')
    ->with('success', 'User created successfully');
});

// PUT route - Update user
Route::put('/users/{id}', function (\Illuminate\Http\Request $request, $id) {
  $user = \App\Models\User::findOrFail($id);

  $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $id
  ]);

  $user->update($validated);

  return redirect()->route('users.show', $id)
    ->with('success', 'User updated successfully');
});

// DELETE route - Delete user
Route::delete('/users/{id}', function ($id) {
  $user = \App\Models\User::findOrFail($id);
  $user->delete();

  return redirect()->route('users.index')
    ->with('success', 'User deleted successfully');
});
```

## 2. Route Parameters and Constraints

```php
// Required parameter - Show single user
Route::get('/user/{id}', function ($id) {
  $user = \App\Models\User::findOrFail($id);
  return Inertia::render('Users/Show', [
    'user' => $user
  ]);
});

// Optional parameter - Posts with optional category
Route::get('/posts/{category?}', function ($category = null) {
  $posts = $category
    ? \App\Models\Post::where('category', $category)->get()
    : \App\Models\Post::all();

  return Inertia::render('Posts/Index', [
    'posts' => $posts,
    'selectedCategory' => $category,
    'categories' => \App\Models\Post::distinct('category')->pluck('category')
  ]);

});

// Parameter constraints - Numeric ID only
Route::get('/user/{id}', function ($id) {
  $user = \App\Models\User::with('posts')->findOrFail($id);
  return Inertia::render('Users/Profile', [
    'user' => $user
  ]);
})->where('id', '[0-9]+');

// Multiple constraints - User profile with slug and ID
Route::get('/user/{name}/{id}', function ($name, $id) {
  $user = \App\Models\User::where('id', $id)
    ->where('slug', $name)
    ->firstOrFail();

  return Inertia::render('Users/PublicProfile', [
    'user' => $user,
    'posts' => $user->posts()->published()->get()
  ]);

})->where(['name' => '[A-Za-z\-]+', 'id' => '[0-9]+']);
```

## 3. Named Routes

```php
// Dashboard with named route
Route::get('/dashboard', function () {
  $stats = [
    'totalUsers' => \App\Models\User::count(),
    'totalPosts' => \App\Models\Post::count(),
    'recentActivity' => \App\Models\Post::latest()->take(5)->get()
  ];

  return Inertia::render('Dashboard', [
      'stats' => $stats
  ]);

})->name('dashboard')->middleware('auth');

// User profile with named route and parameters
Route::get('/user/{id}/profile', function ($id) {
  $user = \App\Models\User::with(['posts', 'followers'])->findOrFail($id);

  return Inertia::render('Users/Profile', [
    'user' => $user,
    'canEdit' => auth()->id() === $user->id
  ]);

})->name('user.profile');

// Edit user form
Route::get('/user/{id}/edit', function ($id) {
  $user = \App\Models\User::findOrFail($id);

  // Authorization check
  if (auth()->id() !== $user->id) {
    abort(403);
  }

  return Inertia::render('Users/Edit', [
    'user' => $user
  ]);

})->name('user.edit')->middleware('auth');
```

## 4. Route Groups and Middleware

```php
// Authentication middleware group
Route::middleware(['auth', 'verified'])->group(function () {

  Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
      'stats' => [
        'posts' => auth()->user()->posts()->count(),
        'drafts' => auth()->user()->posts()->where('status', 'draft')->count()
      ]
    ]);
  })->name('dashboard');

  Route::get('/profile', function () {
    return Inertia::render('Profile/Show', [
      'user' => auth()->user()
    ]);
  })->name('profile');

  // Nested resource routes
  Route::resource('posts', PostController::class);

});

// Admin prefix group with middleware
Route::middleware(['auth', 'admin'])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {

    Route::get('/dashboard', function () {
      return Inertia::render('Admin/Dashboard', [
        'stats' => [
          'totalUsers' => \App\Models\User::count(),
          'totalPosts' => \App\Models\Post::count(),
          'pendingReviews' => \App\Models\Post::where('status', 'pending')->count()
        ]
      ]);
    })->name('dashboard');

    Route::get('/users', function () {
      $users = \App\Models\User::with('posts')
        ->paginate(10);

      return Inertia::render('Admin/Users/Index', [
        'users' => $users
      ]);
    })->name('users.index');

    Route::get('/posts', function () {
      $posts = \App\Models\Post::with('user')
        ->latest()
        ->paginate(15);

      return Inertia::render('Admin/Posts/Index', [
        'posts' => $posts
      ]);
    })->name('posts.index');
  });

// API-style routes for AJAX requests (returns JSON)
Route::middleware(['auth'])->prefix('api')->group(function () {

  Route::get('/user/{id}/posts', function ($id) {
    $posts = \App\Models\User::findOrFail($id)
      ->posts()
      ->latest()
      ->get();

    return response()->json($posts);
  });

  Route::post('/posts/{id}/like', function ($id) {
    $post = \App\Models\Post::findOrFail($id);

    $like = $post->likes()->firstOrCreate([
      'user_id' => auth()->id()
    ]);

    return response()->json([
      'liked' => true,
      'likes_count' => $post->likes()->count()
    ]);
  });

});

// Resource controller routes with Inertia
Route::resource('posts', PostController::class)->middleware('auth');

// Guest routes (for non-authenticated users)
Route::middleware('guest')->group(function () {
  Route::get('/login', function () {
    return Inertia::render('Auth/Login');
  })->name('login');

  Route::get('/register', function () {
    return Inertia::render('Auth/Register');
  })->name('register');

});

// Public routes (accessible to everyone)
Route::get('/', function () {
  $featuredPosts = \App\Models\Post::published()
    ->featured()
    ->with('user')
    ->take(6)
    ->get();

  return Inertia::render('Home', [
    'featuredPosts' => $featuredPosts
  ]);

})->name('home');

Route::get('/about', function () {
  return Inertia::render('About');
})->name('about');

```
