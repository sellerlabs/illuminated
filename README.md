# [illuminated](https://github.com/sellerlabs/illuminated) [![Build Status](https://travis-ci.org/sellerlabs/illuminated.svg?branch=master)](https://travis-ci.org/sellerlabs/illuminated) ![](https://img.shields.io/packagist/v/sellerlabs/illuminated.svg) [![](https://img.shields.io/badge/ApiGen-reference-blue.svg)](http://sellerlabs.github.io/illuminated)

Modular Laravel 5 overlay with many utilities and components (Ranging from stable, experimental to even controversial).

## Components

- **Alerts**: A more formal interface for flashing and
displaying alerts to users of your application.
- **Auth**: Authentication, registration and key pair storage utilities:
    - **HmacMiddleware**: HMAC auth for APIs
    - **KeyPair**: Storage for pairs of public and private keys.
- **Database**:
    - Structured Migrator
    - Utility migration classes
    - **JsonModels**: Models that automatically serialize and de-serialize JSON on specified fields as they come and go to the database.
- **Hashing**: Aggregated hasher for automatically upgrading legacy hashes (MD5) to more secure ones on projects working existing databases or user data.
- **Http**: HTTP and routing utilities
    - **Route Mapper Interface**: A simple interface for defining class versions of the `routes.php` file. TestCase included.
    - **Route Aggregator**: Aggregates routes defined in classes implemeting the RouteMapper interface.
    - **ApiResponse**: An opinionated API response generator.
    - **ResourceFactory**: Build groups of routes faster and with fewer lines.
- **Jobs**: A task scheduling and management framework.
- **Testing**: A collection of PHPUnit test cases for testing general and specific kinds of classes:
    - LaravelTestCase
    - ModelTestCase
    - RouteMapperTestCase
    - ServiceProviderTestCase
- **Queue**: Helpers for pushing queue jobs into SQS by the queue name, not its address.
- **Style**:
    - **CSS Inliner Service**: Write your email templates using blade and do not worry about whether or not they will display correctly. The inliner service is capable of inlining a specified CSS file into a view.
- **Support**:
    - **ServiceProvider**: Exactly like a regular Laravel service provider but it explicitly requires the developer to define `$defer`.
    - **ServiceMapProvider**: A shortcut for creating service providers with fewer lines of code.

## Setup

Each service in this package should be loadable by using the corresponding
service provider. This is easily done by adding the provider to your app.php:

```php
return [
    // ...
    'providers' => [
        // Third-party service providers...
        SellerLabs\Illuminated\Inliner\InlinerServiceProvider::class
    ];
];
```

Some modules might have specific exceptions or additional instructions. Make sure to read the corresponding README file.

## Contributing

Pull requests are accepted on GitHub. Bug fixes and small improvements are welcome. Big ideas will be reviewed and discussed.

Code Standard: PSR-2 with some additions. See https://github.com/sellerlabs/php-standard for more details.

## License

This code is licensed under the MIT license. See LICENSE for more information.
