# Laravel Basics - Routing

This files refer to only Laravel. To use Inertia and React, please use the [Routing Guide with Inertia](routing_inertia-react.md)

## Laravel Standard Conventions for RESTful Resource

| HTTP Method | URI               | Controller Method | Route Name     | Purpose            |
| ----------- | ----------------- | ----------------- | -------------- | ------------------ |
| GET         | `/post`           | `index`           | `post.index`   | List all posts     |
| GET         | `/post/create`    | `create`          | `post.create`  | Show create form   |
| POST        | `/post`           | `store`           | `post.store`   | Store new post     |
| GET         | `/post/{id}`      | `show`            | `post.show`    | Show specific post |
| GET         | `/post/{id}/edit` | `edit`            | `post.edit`    | Show edit form     |
| PUT/PATCH   | `/post/{id}`      | `update`          | `post.update`  | Update post        |
| DELETE      | `/post/{id}`      | `destroy`         | `post.destroy` | Delete post        |

### Good Controller Method Names

- `index` - List/display multiple items
- `show` - Display single item
- `create` - Show form to create new item
- `store` - Process form and save new item
- `edit` - Show form to edit existing item
- `update` - Process form and update existing item
- `destroy` - Delete item

### Good Route Names

- `hello.index` - List page
- `hello.show` - Detail page
- `hello.create` - Create form
- `hello.store` - Store action
- `hello.edit` - Edit form
- `hello.update` - Update action
- `hello.destroy` - Delete action

### Custom Route Names

- `hello.search` - Search functionality
- `hello.export` - Export data
- `hello.import` - Import data
- `hello.ordered` - Custom ordering/filtering

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
