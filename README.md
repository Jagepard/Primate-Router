[![PHPunit](https://github.com/Jagepard/Primate-Router/actions/workflows/php.yml/badge.svg)](https://github.com/Jagepard/Primate-Router/actions/workflows/php.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-498e7f.svg)](https://mit-license.org/)

# Primate-Router

### Прмиитивный маршрутизатор, для упрощения понимания логики. | [API](https://github.com/Jagepard/Primate-Router/blob/master/api.md "Documentation API")

Использование:

```php
use Primate\Router\Router;

$router = new Router();
```
Далее обязательно разбираем "REQUEST_URI"
```php
$requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
```

После добавления всех маршрутов вызываем метод сопоставление маршрутов с данными запроса "REQUEST_URI"
```php
$router->matchRoute($requestUri);
```

## Добавление маршрутов:

В случае перехода по адресу /closure в браузере, 
в окне будет отображено "Hello World!",
по умолчанию $_SERVER["REQUEST_METHOD"] === "GET"
```php
$router->addRoute("/closure", function () {
    echo "Hello World!";
});
```
В случае перехода по адресу /closure/john в браузере,
в окне будет отображено "Hello john!"
```php
$router->addRoute("/closure{name}", function ($name) {
    echo "Hello $name!";
});
```
При изменении метода запроса необходимо также его указать 3 параметром при добавлении маршрута
```php
$router->addRoute("/closure", function () {
    echo "Hello World!";
}, "POST");
```
