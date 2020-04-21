<?php
declare(strict_types=1);

require_once('../includes/init.php');

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

/**
 * ROUTER
 */

$router = new League\Route\Router;

// Auth middleware
$router->middleware(new App\Middleware\AuthMiddleware);

// Normal connection route
$router->map('GET', '/', 'App\Controllers\MainController::getProfile');

// redirect to site
$router->map('GET', '/redirect', 'App\Controllers\RedirectController::handle');

// Login routes
$router->map('GET', '/login', 'App\Controllers\MainController::test');


/**
 * RESPONSE
 */

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);