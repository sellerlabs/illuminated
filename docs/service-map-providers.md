# Service Map Providers

A `ServiceMapProvider` is an overly-simplified implementation of a service
provider. It's main purpose is to map interfaces (abstracts) to an
implementation such as a class or factory function. The resulting provider
is usually shorter and easier to read.

`ServiceMapProvider`s are abstract and are intended to be extended by the
developer. The following properties should be modified by the implementation:

- `$map`: An array mapping an interface class name (key) with an implementation
class name (value).
- `$singletons`: Like `$map` but for classes that should be bound as
singletons.
- `$commands`: Name of command classes that should be registered by the
provider.
- `$defer`: Whether the provider should be deferred or not.

On `ServiceMapProvider`s, the `register()` and `provides()` methods are
implemented for you.

**Example:**

```php
class UserServiceProvider extends ServiceMapProvider
{
    protected $defer = true;

    protected $map = [
        UserRepositoryInterface::class => UserRepository::class,
        ProfileManagerInterface::class => ProfileManager::class,
    ];
}
```

## Factory functions

If you need to bind an abstract to a closure, you will notice that it is not
possible to include closures in class property default values on PHP. However,
you can still make use of `ServiceMapProvider`s by overriding the
`getServiceMap()` method (`getSingletons()` for singletons) and returning your
map from there.

```php
class UserServiceProvider extends ServiceMapProvider
{
    protected $defer = true;

    protected function getServiceMap()
    {
        return [
            UserRepositoryInterface::class => UserRepository::class,
            ProfileManagerInterface::class => function ($app) {
                return new ProfileManager($app['config']->get('profiles'));
            },
        ];
    }
}
```

## Limitations

If you need custom logic on `register()` or `provides()`, it is recommended
that you stick to regular service providers. `boot()` is still available on
`ServiceMapProvider`s.
