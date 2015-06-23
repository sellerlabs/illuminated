# laravel-helpers

This is a collection of some utility classes and components that I use
on my Laravel 5 projects. Some of them are experimental and might not
be finished or tested.

## Components

- [**Database**](https://github.com/etcinit/laravel-helpers/blob/master/src/Chromabits/Illuminated/Database/README.md):
    - Structured Migrator
    - Namespaced Migrator
    - Utility migration classes
- **Alerts Service (WIP)**: A more formal interface for flashing and
displaying alerts to users of your application.
- [**CSS Inliner Service**]((https://github.com/etcinit/laravel-helpers/blob/master/src/Chromabits/Illuminated/Inliner/README.md)): Write your email templates using blade and
do not worry about whether or not they will display correctly. The
inliner service is capable of inlining a specified CSS file into a view.
- **Route Mapper Interface**: A simple interface for defining class versions
of the `routes.php` file. TestCase included.
- **Jobs (WIP)**: A job scheduling and management framework.
- [**Testing**](https://github.com/etcinit/laravel-helpers/blob/master/src/Chromabits/Illuminated/Testing/README.md):
    - LaravelTestCase
    - ModelTestCase
    - RouteMapperTestCase
    - ServiceProviderTestCase 

## Setup

Each service in this package should be loadable by using the corresponding
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

Some modules might have specific exceptions or additional instructions. Make sure to read the corresponding README file.
