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
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan route:clear
# Validate new routes
./vendor/bin/sail artisan route:list
```

Generate new data seeds

```bash
./vendor/bin/sail artisan db:seed --class=HelloSeeder
# Or by using a simpler syntax
./vendor/bin/sail artisan db:seed HelloSeeder
```

### Backend Development with TDD
