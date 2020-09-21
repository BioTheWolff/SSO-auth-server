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

// Index
$router->map('GET', '/', 'App\Controllers\LoginController::index');

// SSO
$router->map('GET', SSO_VERIFY, 'App\Controllers\SSOController::verifyToken');
$router->map('GET', SSO_PUBKEY, 'App\Controllers\SSOController::givePubkey');
$router->map('GET', SSO_AUTH, 'App\Controllers\SSOController::handleRedirect');

// Log-in/out routes
$router->map('GET', USER_LOGIN, 'App\Controllers\LoginController::renderLoginForm');
$router->map('POST', USER_LOGIN, 'App\Controllers\LoginController::verifyLogin');
$router->map('GET', USER_LOGOUT, 'App\Controllers\LoginController::logout');

// Admin panel
$router->group(ADMIN_PART, function (\League\Route\RouteGroup $route) {
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