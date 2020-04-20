<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

function new_router() {

    $router = new League\Route\Router;

    // Normal connection route
    $router->map('GET', '/', function (ServerRequestInterface $request) : ResponseInterface {
        $response = new Laminas\Diactoros\Response;
        $response->getBody()->write('<h1>Hello, World!</h1>');
        return $response;
    });

    // authenticate to redirect to site
    $router->map('GET', '/redirect', function (ServerRequestInterface $request) : ResponseInterface {
        $response = new Laminas\Diactoros\Response;
        $response->getBody()->write('<h1>Redirect !</h1>');
        return $response;
    });

    $router->map('GET', '/test', function (ServerRequestInterface $request) : ResponseInterface {
        $templates = new League\Plates\Engine(dirname(__DIR__) . '/' . 'templates/');

        $response = new Laminas\Diactoros\Response\HtmlResponse(
            $templates->render('profile', ['name' => 'Jonathan']),
            200,
            ['Content-Type' => ['text/html']]
        );
        return $response;
    });

    return $router;
    
}
