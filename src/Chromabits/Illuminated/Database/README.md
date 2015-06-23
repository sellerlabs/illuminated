# Database Component

## Modules

- **Structured Migrations**: A much different approach to migrations in Laravel. Migrations are organized in classes (PSR-2 compatible), which makes it easy to organize things neatly, performed advanced verification through introspection, or display a GUI with the status of migrations. The main con with this approach is how non-standard it is and that migration files will no longer be sorted in the order they are applied.
- **Namespaced Migrations**: Sort of the precursor to Structured Migrations. A small hack to put regular Laravel migrations inside a namespace. It has no benefit other than neat directory structure. Might be deprecated in the future.

## Utility Classes

- **BaseMigration**: A simple migration which includes an internal reference to the current connection, the schema builder and some utility classes. This make it posible to avoid using the `Schema::` facade, which makes IDEs happy even though it's not functially that different.
- **TableMigration**: A simplification of the most common migration: Create a table on `up()` and drop it on `down()`. Simply provide a table name and implement the `create()` method and you're good to go. It's benefit becomes aparent when you have 20+ migrations doing the exact same thing.


## Module-specific instructions:

### Structured Migrations

This module has three main parts you should be aware of while using it:

- Migration classes
- Batch classes
- The Migrator class

**Migration classes** are exactly the same as migrations in vanilla Laravel. The main difference is that they are situated inside a namespace along the rest of your code, and the filename matches the class name (which keeps linters happy).

A **Batch class** is simply a class that represents a grouping of migrations and in which order they should be ran. The default behavior assumes that the migration will have the same name as its class (Ex: `CreateUserTable`). However, since some projects may already have migrations ran, Batch classes allow you to define aliases. For example: `2014_09_12_172725_create_user_table` can be aliased to `App\Database\Migrations\CreateUserTable`.

```php
<?php

namespace App\Database\Migrations;

use Chromabits\Illuminated\Database\Migrations\Batch;
use App\Database\Migrations\CreateUserTable;
use App\Database\Migrations\CreatePagesTable;

class V1Batch extends Batch
{
    public function getMigrations()
    {
        return [
            // Here, we reference the alias defined below, 
            // which allows us to keep using previous migrations.
            '2014_09_12_172725_create_user_table',
            
            // This is a regular definition.
            'App\Database\Migrations\CreatePostsTable',
            
            // On recent PHP versions, this is possible too:
            CreatePagesTable::class,
            
            // Batches can use definitions in other batches.
            // Once you have many migrations, its probably good
            // to split them into separate batches and have a
            // the main batch class reference it.
            new CommentsBatch(),
        ];
    }

    public function getAliases()
    {
        return [
            '2014_09_12_172725_create_user_table' => CreateUserTable::class
        ];
    }
}
```

The last part of the puzzle is the **Migrator class**. Before you can interact with it, you need to define which Batch class is the root batch in your application. You can do this by simply binding the `Batch` type with your custom class on a service provider:

```php
$this->app->bind(Batch::class, MainBatch::class);
```

After the main class is defined, you should be able to interact with the migrator using the `artisan` CLI. This library currently only implements a small fraction of the migrate commands:

```
php artisan structured:install
php artisan structured:migrate
php artisan structured:status
```

### Namespaced Migrations

The **NamespacedMigrator** needs a
 custom replacement of **ConsoleSupportServiceProvider** in order to be able to use it. This is due to the fact that it ovverrides the `migrate` family of CLI commands. An example of how
 you could write one for your app is included below:
 
 ```php
 <?php
 
 namespace App\Providers;
 
 use Illuminate\Support\AggregateServiceProvider;
 
 /**
  * Class ConsoleSupportServiceProvider
  *
  * @package App\Providers
  */
 class ConsoleSupportServiceProvider extends AggregateServiceProvider
 {
     /**
      * Indicates if loading of the provider is deferred.
      *
      * @var bool
      */
     protected $defer = true;
     /**
      * The provider class names.
      *
      * @var array
      */
     protected $providers = [
         'Illuminate\Auth\GeneratorServiceProvider',
         'Illuminate\Console\ScheduleServiceProvider',
         'Chromabits\Illuminated\Database\NamespacedMigrationServiceProvider',
         'Illuminate\Database\SeedServiceProvider',
         'Illuminate\Foundation\Providers\ComposerServiceProvider',
         'Illuminate\Queue\ConsoleServiceProvider',
         'Illuminate\Routing\GeneratorServiceProvider',
         'Illuminate\Session\CommandsServiceProvider',
     ];
 }
 ```
