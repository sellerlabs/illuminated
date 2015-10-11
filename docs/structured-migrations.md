### Structured Migrations

This module has three main parts you should be aware of while using it:

- Migration classes
- Batch classes
- The Migrator class

**Migration classes** are exactly the same as migrations in vanilla Laravel.
The main difference is that they are situated inside a namespace along the rest
of your code, and the filename matches the class name (which keeps linters
happy).

A **Batch class** is simply a class that represents a grouping of migrations
and in which order they should be ran. The default behavior assumes that the
migration will have the same name as its class (Ex: `CreateUserTable`).
However, since some projects may already have migrations ran, Batch classes
allow you to define aliases. For example: `2014_09_12_172725_create_user_table`
can be aliased to `App\Database\Migrations\CreateUserTable`.

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

The last part of the puzzle is the **Migrator class**. Before you can interact
with it, you need to define which Batch class is the root batch in your
application. You can do this by simply binding the `Batch` type with your
custom class on a service provider:

```php
$this->app->bind(Batch::class, MainBatch::class);
```

After the main class is defined, you should be able to interact with the
migrator using the `artisan` CLI. This library currently only implements a
small fraction of the migrate commands:

```
php artisan structured:install
php artisan structured:migrate
php artisan structured:status
```
