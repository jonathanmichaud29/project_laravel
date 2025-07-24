# Practice Project - simple "Hello World"

Create a simple "Hello World" application with multiple routes

## Objectives

- Create new table 'Hello' on which it will contains a list of unique words
- Create those routes with '/hello' as prefixes
  - **GET '/'** : List all words with an option to create a new word
  - **POST '/'** : Create new word in database
  - **GET '/order'** : List words in alphabetical order

## Steps

### Database Design

```bash
# Create a controller with resource methods (index, create, store, show, edit, update, destroy)
./vendor/bin/sail artisan make:controller HelloController --resource

# Create model with every options (migration, factory, seeder, policy, controller)
./vendor/bin/sail artisan make:model Hello --all
# Or create a model with migration,factory and seeder
./vendor/bin/sail artisan make:model Hello -mfs
```

Adjust the migration file `/my-app/database/migrations/<migration-file>.php`

Adjust the model `/my-app/app/Models/Hello.php`

Define new routes in `routes/web.php`

Call scripts to apply migration and clear routes

```bash
# Apply DB Migration
./vendor/bin/sail artisan migrate
# Clear and validate routes
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan route:list
```

Generate new data seeds

```bash
./vendor/bin/sail artisan db:seed --class=HelloSeeder
# Or by using a simpler syntax
./vendor/bin/sail artisan db:seed HelloSeeder
```

### Backend Development with TDD

Create a simple test file `tests/Feature/HelloPageTest.php` with few test cases, then execute the script

```bash
# Run the test
./vendor/bin/sail artisan test tests/Feature/HelloPageTest.php
```

Tests fail because routes and controllers don't exist yet

Edit the controller `app/Http/Controllers/HelloController.php` :

- Create a query to fetch database items
- Render a view by defining some parameters, like database query results
- Create JSX files for each views

Relaunch the test file and they should pass.

Create Unit Test with new file `tests/Unit/HelloTest.php` and create a simple creation test. If relations can exist with the type of data, validating those relations would be done there.
