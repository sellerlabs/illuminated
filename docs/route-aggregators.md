# Route Aggregators

A route aggregator is a class that implements the `RouteMapper` interface
and does this mapping by aggregating the routes mapper by other mapper classes.

`RouteAggregator` is an abstract class and is intended to be extended. The
mappers that are aggregated are defined in the `$mappers` property. The
aggregator will resolve instances of the mappers through the IOC container
and call `map()` on each one of them in the order they were provided.

**Example:**

```php
class RouteDirectory extends RouteAggregator
{
    protected $mappers = [
        IndexRouteMapper::class,
        UserRouteMapper::class,
        PostRouteMapper::class,
        CommentRouteMapper::class,
        DashboardRouteMapper::class,
    ];
}
```
