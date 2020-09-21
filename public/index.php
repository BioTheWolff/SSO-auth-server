<?php
declare(strict_types=1);

use League\Route\RouteGroup;

require_once('../includes/init.php');

// Define the request
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

/**
 * ROUTER PARAMS
 */
$router = new League\Route\Router;

// Strategy
$strategy = new App\Strategies\FancyStrategy;
$router->setStrategy($strategy);

// Middleware
$router->middleware(new App\Middleware\AuthMiddleware);


/**
 * ROUTES
 */
// Index
$router->map('GET', '/', 'App\Controllers\LoginController::index');

// CAS-related (verify token, redirect to broker)
if (ALLOW_PUBKEY_ROUTE == true) {
    $router->map('GET', CAS_PUBKEY, 'App\Controllers\CASController::givePubkey');
}
$router->map('GET', CAS_VERIFY, 'App\Controllers\CASController::verifyToken');
$router->map('GET', CAS_AUTH, 'App\Controllers\CASController::handleRedirect');

// Log-in/out
$router->map('GET', USER_LOGIN, 'App\Controllers\LoginController::renderLoginForm');
$router->map('POST', USER_LOGIN, 'App\Controllers\LoginController::verifyLogin');
$router->map('GET', USER_LOGOUT, 'App\Controllers\LoginController::logout');

// Admin part of the website
$router->group(ADMIN_PART, function (RouteGroup $route) {
    // Panel
    $route->map('GET', '/panel', 'App\Controllers\AdminController::adminPanel');
    
    // Create new user
    $route->map('GET', '/new', 'App\Controllers\AdminController::newUser');
    $route->map('POST', '/new', 'App\Controllers\AdminController::handleNewUser');


})->middleware(new App\Middleware\AdminMiddleware);


/**
 * RESPONSE
 */
$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);