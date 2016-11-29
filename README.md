# Router Middleware

A simple PSR-15 compatible router middleware, based on 
[Fast Route](https://github.com/nikic/FastRoute).

## Install

Via Composer 

```bash
$ composer require ideationnet/router-middleware
```

## Usage

Use with your favourite PSR-15 middleware dispatcher.
Inject an instance of `Dispatcher`.
See [Wafer](https://github.com/ideationnet/wafer) for an example of this used with 
PHP-DI...

```php
return [

    'routes' => [],
    
    Dispatcher::class => function (ContainerInterface $c) {
        return simpleDispatcher(function (RouteCollector $r) use ($c) {
            array_map(function ($route) use ($r) {
                call_user_func_array([$r, 'addRoute'], $route);
            }, $c->get('routes'));
        });
    },
    
];
```

## Security

If you discover any security related issues, please email
darren@darrenmothersele.com instead of using the issue tracker.


## Credits

- [Darren Mothersele](http://www.darrenmothersele.com)
- [All Contributors](../../contributors)

## License

The MIT License. Please see [License File](License.md) for more information.

