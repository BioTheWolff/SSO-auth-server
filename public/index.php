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

// Strategy
$strategy = new App\Strategies\FancyStrategy;
$router->setStrategy($strategy);

// Middleware
$router->middleware(new App\Middleware\AuthMiddleware);

// Profile
$router->map('GET', '/', 'App\Controllers\MainController::redirectToProfile');
$router->map('GET', '/profile', 'App\Controllers\MainController::getProfile');

// Connection then redirection
$router->map('GET', '/redirect', 'App\Controllers\RedirectController::handle');

// Login routes
$router->map('GET', '/login', 'App\Controllers\LoginController::renderLoginForm');
$router->map('POST', '/login', 'App\Controllers\LoginController::verifyLogin');

// Log out route
$router->map('GET', '/logout', 'App\Controllers\LoginController::logout');


/**
 * RESPONSE
 */

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);