<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 *
 *  phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
 */

namespace Primate\Router\Tests;

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;
use Primate\Router\Router;
use Primate\Router\Tests\Stub\Controllers\MainController;

class RouterTest extends PHPUnit_Framework_TestCase
{
    protected function setUp(): void
    {
        require "./vendor/autoload.php";
    }

    protected function setRouteEnvironment(string $requestUri, string $requestMethod, string $pattern): void
    {
        $_SERVER["REQUEST_URI"]    = $requestUri;
        $_SERVER["REQUEST_METHOD"] = $requestMethod;

        $router = new Router();
        $requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
        $router->addRoute($pattern, [MainController::class, "index"], $requestMethod);
        $router->matchRoute($requestUri);

        $this->expectOutputString('Primate\Router\Tests\Stub\Controllers\MainController::index');
    }

    public function testGet(): void
    {
        $this->setRouteEnvironment("/test/page?id=98", "GET", "/test/page");
    }

    public function testPost(): void
    {
        $this->setRouteEnvironment("test/page?some=123", "POST", "/test/page");
    }

    public function testPut(): void
    {
        $this->setRouteEnvironment("test/page", 'PUT', "/test/page");
    }

    public function testPatch(): void
    {
        $this->setRouteEnvironment("test/page", 'PATCH', "/test/page");
    }

    public function testDelete(): void
    {
        $this->setRouteEnvironment("test/page", 'DELETE', "/test/page");
    }

    public function testOption(): void
    {
        $this->setRouteEnvironment("test/page", 'OPTION', "/test/page");
    }

    public function testCallable(): void
    {
        $_SERVER["REQUEST_URI"]    = "test/page";
        $_SERVER["REQUEST_METHOD"] = "GET";

        $router = new Router();
        $requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));
        $router->addRoute("/test/page", function () {
            echo "Closure";
        });

        $router->matchRoute($requestUri);

        $this->expectOutputString('Closure');
    }
}
