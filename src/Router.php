<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Primate\Router;

class Router
{
    private array $routes;

    public function addRoute(string $uri, array|callable $target, string $requestMethod = "GET", string $name = null): void
    {
        $this->routes[] = [
            "URI" => $uri,
            "REQUEST_METHOD" => $requestMethod,
            "TARGET" => $target,
            "ROUTE_NAME" => $name
        ];
    }

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
                    if (preg_match('/{([a-zA-Z0-9_]*?)}/', $routeUri[$i]) !== 0) {
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
                        (is_array($params)) ? new $route["TARGET"](...$params) : $route["TARGET"]($params);
                        return;
                    }

                    $this->callAction($params, $route["TARGET"][1], new $route["TARGET"][0]);
                    return;
                }
            }
        }

        http_response_code(404);
    }

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
