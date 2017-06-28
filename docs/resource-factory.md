# Resource Factory

When building large APIs on Laravel projects, the `routes.php` can grow to the
point where it is hard to read or just too long. Illuminated provides a simple
factory class for route groups. While they can be less flexible that Laravel's
route groups, they are a lot smaller, require less typing, and are generally
easier to read.

The `ResourceFactory` class optimizes one of the most common patterns:
Adding routes to methods in a controller, with a common prefix and a common
set of middleware.

**Example:**

Inside a `routes.php` or a `RouteMapper`:
```php
ResourceFactory::create(UsersApiController::class)
    ->withMiddleware(['api.auth'])
    ->withPrefix('/v1/users')
    ->get('/', 'getIndex')
    ->get('/{id}', 'getSingle')
    ->post('/{id}', 'postSingle')
    ->inject($router);
```

This factory will generate a new route group with the prefix `/v1/users`
and `api.auth` middleware. All routes belong to the same controller class
`UsersApiController`. The action names are automatically generated: For example,
`/` has the action: `App/Users/Controllers/UsersApiController@getIndex`.

## Within route groups:

You can also use resource factories inside route groups whenever you need to do
something the factory does not provide:

```php
// API v1
Route::group(['prefix' => '/v1'], function (Router $router) {
    ResourceFactory::create(UsersApiController::class)
        ->withMiddleware(['api.auth'])
        ->withPrefix('/users')
        ->get('/', 'getIndex')
        ->get('/{id}', 'getSingle')
        ->post('/{id}', 'postSingle')
        ->inject($router);

    ResourceFactory::create(PostsApiController::class)
        ->withPrefix('/users')
        ->get('/', 'getIndex')
        ->get('/{id}', 'getSingle')
        ->inject($router);
});
```
