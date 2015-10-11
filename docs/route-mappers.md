# Route Mappers

The `RouteMapper` interface simply describes a class capable of mapping routes
to their actions (a.k.a. defining routes).

Having multiple mapper classes throughout your application allows you to split
large `routes.php` files into smaller units that are easy to understand and
read.

Having a common interface also allows for things like the `RouteAggregator`
class, which a `RouteMapper` that aggregates many other `RouteMapper`s.

## Injecting mappers:

If you start with a blank Laravel application, you will have a `routes.php`
file setup for you. If you would like to try out using mappers in your
application, you need to load them into your application.

Laravel applications come with a `RouteServiceProvider`. On this provider,
you can see how the `routes.php` file is loaded.

To inject/load mappers, all you need to do is create an instance of the class
and call `$mapper->map($router)`:

```php
public function map(Router $router)
{
    $router->group(['namespace' => $this->namespace], function ($router) {
        require app_path('Http/routes.php');
    });

    $mapper = new MyMapper();
    $mapper->map($router);
}
```

To simplify this, you can create a main `RouteAggregator` class for your
application, and inject it once of the `RouteServiceProvider`.

## Organizing mappers:

Depending on the size of your application, you may decide to group all your
mappers on a single namespace (like `App/Routes`), or, in the case of a larger
one, put a mapper on the namespace of the module/component they cover:
`App/Users/UsersRouteMapper` or `App/Posts/PostsRouteMapper`.
