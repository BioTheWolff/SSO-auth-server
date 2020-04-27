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

// User profile
$router->map('GET', '/', 'App\Controllers\ProfileController::redirectToProfile');
$router->map('GET', USER_PROFILE, 'App\Controllers\ProfileController::getProfile');

$router->group(USER_PROFILE, function (\League\Route\RouteGroup $route) {
    // Edit profile
    $route->map('GET', '/edit', 'App\Controllers\ProfileController::editProfile');
    $route->map('POST', '/edit', 'App\Controllers\ProfileController::handleEditProfile');

    $route->map('GET', '/password', 'App\Controllers\ProfileController::changePassword');
    $route->map('POST', '/password', 'App\Controllers\ProfileController::handleChangePassword');
});

// SSO
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
})->middleware(new App\Middleware\AdminMiddleware);


/**
 * RESPONSE
 */

$response = $router->dispatch($request);

// send the response to the browser
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);