# Service Providers

On Illuminated, service providers behave just like service providers on
Laravel, with the small exception that they require the developer to explicitly
set the value of the `$defer` property.

This ensures that the developer does not accidentally forget to declare a
provider as deferred, which could have negative performance implications.
