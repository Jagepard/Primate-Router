<?php

use App\Controllers\MainController;
use Primat\Router\Router;

require "../vendor/autoload.php";

$router = new Router();
$requestUri = explode('/', trim(parse_url($_SERVER["REQUEST_URI"])["path"], '/'));

$router->addRoute("/comments/{name}", [MainController::class, "index"]);
$router->addRoute("/comment", [MainController::class, "comment"], "POST");
$router->addRoute("/comment", [MainController::class, "comment"], "DELETE");
$router->matchRoute($requestUri);


