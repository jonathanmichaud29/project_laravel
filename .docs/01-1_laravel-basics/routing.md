# Laravel Basics - Routing

This files refer to only Laravel. To use Inertia and React, please use the [Routing Guide with Inertia](routing_inertia-react.md)

## 1. Basic Routes

```php
// GET route
Route::get('/users', function () {
  return 'List of users';
});

// POST route
Route::post('/users', function () {
  return 'Create a user';
});

// PUT route
Route::put('/users/{id}', function ($id) {
  return 'Update user ' . $id;
});

// DELETE route
Route::delete('/users/{id}', function ($id) {
  return 'Delete user ' . $id;
});
```

## 2. Route Parameters and Constraints

```php
// Required parameter
Route::get('/user/{id}', function ($id) {
  return 'User ID: ' . $id;
});

// Optional parameter
Route::get('/posts/{id?}', function ($id = null) {
  return $id ? 'Post ' . $id : 'All posts';
});

// Parameter constraints
Route::get('/user/{id}', function ($id) {
  return 'User ID: ' . $id;
})->where('id', '[0-9]+');

// Multiple constraints
Route::get('/user/{name}/{id}', function ($name, $id) {
  return "User: $name, ID: $id";
})->where(['name' => '[A-Za-z]+', 'id' => '[0-9]+']);
```

## 3. Named Routes

```php
// Define named route
Route::get('/dashboard', function () {
  return view('dashboard');
})->name('dashboard');

// Generate URL using route name
$url = route('dashboard'); // Returns '/dashboard'

// Redirect to named route
return redirect()->route('dashboard');

// Named route with parameters
Route::get('/user/{id}', function ($id) {
  return view('user.profile', compact('id'));
})->name('user.profile');

// Generate URL with parameters
$url = route('user.profile', ['id' => 1]); // Returns '/user/1'
```

## 4. Route Groups and Middleware

```php
// Middleware group
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Only authenticated users can access
    });
    Route::get('/profile', function () {
        // Only authenticated users can access
    });
});

// Prefix group
Route::prefix('admin')->group(function () {
    Route::get('/users', function () {
        // URL: /admin/users
    });
    Route::get('/posts', function () {
        // URL: /admin/posts
    });
});

// Combined attributes
Route::middleware(['auth'])
  ->prefix('admin')
  ->name('admin.')
  ->group(function () {
    Route::get('/dashboard', function () {
      // URL: /admin/dashboard
      // Name: admin.dashboard
      // Requires authentication
    })->name('dashboard');
  });
```
