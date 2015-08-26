# [illuminated](http://phabricator.chromabits.com/diffusion/LLMNTD/) [![Build Status](https://travis-ci.org/etcinit/illuminated.svg?branch=master)](https://travis-ci.org/etcinit/illuminated) ![](https://img.shields.io/packagist/v/chromabits/illuminated.svg) [![](https://img.shields.io/badge/ApiGen-reference-blue.svg)](http://etcinit.github.io/illuminated)

Modular Laravel 5 overlay with many utilities and components (Ranging from stable, experimental to even controversial).

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
        Chromabits\Illuminated\Inliner\InlinerServiceProvider::class
    ];
];
```

Some modules might have specific exceptions or additional instructions. Make sure to read the corresponding README file.

## Contributing

Pull requests are accepted on GitHub. Bug fixes and small improvements are welcome. Big ideas will be reviewed and discussed.

Code Standard: PSR-2 with some additions. See https://github.com/etcinit/php-coding-standard for more details.

## Security

If you discover any security related issues, please email ed+security@chromabits.com instead of using the issue tracker.

## License

This code is licensed under the MIT license. See LICENSE for more information.
