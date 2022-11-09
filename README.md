# Primate-Router

### Прмиитивный маршрутизатор, для упрощения понимания логики.

Использование:

```php
use Primate\Router\Router;

$router = new Router();
```

## Добавление маршрутов:

В случае перехода по адресу /closure в браузере,
в окне будут отображено "Hello World!"
```php
$router->addRoute("/closure", function () {
    echo "Hello World!";
});
```
В случае перехода по адресу /closure/john в браузере,
в окне будут отображено "Hello john!"
```php
$router->addRoute("/closure{name}", function ($name) {
    echo "Hello $name!";
});
```
