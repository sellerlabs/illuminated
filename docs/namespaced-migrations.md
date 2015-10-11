### Namespaced Migrations

The **NamespacedMigrator** needs a
custom replacement of **ConsoleSupportServiceProvider** in order to be able to 
use it. This is due to the fact that it overrides the `migrate` family of CLI
commands. An example of how you could write one for your app is included below:

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
