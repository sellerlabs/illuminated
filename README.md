# laravel-helpers

This is a collection of some utility classes and components that I use
on my Laravel 5 projects. Some of them are experimental and might not
be finished or tested.

## Contents

- **Namespaced Migrator**: A modified Laravel 5 database migrator with
the capability of loading migrations that are namespaced. This should
helping making your code more PSR-2 compliant, however, at the moment
filenames are still not PSR-2 compliant. (NAMESPACE ALL THE THINGS!!)
- **Alerts Service (WIP)**: A more formal interface for flashing and
displaying alerts to users of your application.
- **CSS Inliner Service**: Write your email templates using blade and
do not worry about whether or not they will display correctly. The
inliner service is capable of inlining a specified CSS file into a view.

## Setup

Each service in this package should be loadable as using the corresponding
service provider. This is easily done by adding the provider to your app.php:

```php
return [
    // ...
    'providers' => [
        // Third-party service providers...
        'Chromabits\Illuminated\Inliner\InlinerServiceProvider'
    ];
];
```

The exception to the method provided above is the **NamespacedMigrator** which needs a
 custom replacement of **ConsoleSupportServiceProvider**. An example of how
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
