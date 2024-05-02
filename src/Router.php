<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Primate\Router;

class Router
{
    private array $routes;

    /**
     * Adds a route
     * ------------
     * Добавляет маршрут
     *
     * @param  string         $uri
     * @param  array|callable $target
     * @param  string         $requestMethod
     * @param  string|null    $name
     * @return void
     */
    public function addRoute(string $uri, array|callable $target, string $requestMethod = "GET", string $name = null): void
    {
        $this->routes[] = [
            "URI" => $uri,
            "REQUEST_METHOD" => $requestMethod,
            "TARGET" => $target,
            "ROUTE_NAME" => $name
        ];
    }

    /**
     * Matches a request with a route
     * ------------------------------
     * Сопоставляет запрос с маршрутом
     *
     * @param  array $requestUri
     * @return void
     */
    public function matchRoute(array $requestUri): void
    {
        foreach ($this->routes as $route) {
            if ($route["REQUEST_METHOD"] === $_SERVER["REQUEST_METHOD"]) {
                $routeUri = explode('/', trim($route["URI"], '/'));
                $count = count($routeUri);
                $uri = [];
                $params = null;

                for ($i = 0; $i < $count; $i++) {
                    // Looking for a match with a pattern {...}
                    if (preg_match('/:([a-zA-Z0-9_]*?)/', $routeUri[$i]) !== 0) {
                        if (array_key_exists($i, $requestUri)) {
                            $uri[] = $requestUri[$i];
                            $params[] = $requestUri[$i];
                        }

                        continue;
                    }

                    $uri[] = $routeUri[$i];
                }

                if ($requestUri === $uri) {
                    if ($route["TARGET"] instanceof \Closure) {
                        (is_array($params)) ? $route["TARGET"](...$params) : $route["TARGET"]($params);
                        return;
                    }

                    $this->callAction($params, $route["TARGET"][1], new $route["TARGET"][0]);
                    return;
                }
            }
        }

        http_response_code(404);
    }

    /**
     * Calls the required controller method
     * ------------------------------------
     * Вызывает необходимый метод контроллера
     *
     * @param  $params
     * @param  $action
     * @param  $controller
     * @return void
     */
    protected function callAction($params, $action, $controller): void
    {
        if (!isset($params)) {
            $controller->{$action}();
        } else {
            if (!in_array("", $params)) {
                $controller->{$action}(...$params);
            }
        }
    }
}
