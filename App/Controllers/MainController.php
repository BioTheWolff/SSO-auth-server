<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MainController {

    public function getProfile(ServerRequestInterface $request) : ResponseInterface {
        $response = new \Laminas\Diactoros\Response;
        $response->getBody()->write('<h1>Hello, World!</h1>');
        return $response;
    }

    public function test(ServerRequestInterface $request) : ResponseInterface {
        $templates = new \League\Plates\Engine(dirname(__DIR__) . '/../' . 'templates/');

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            $templates->render('profile', ['name' => 'Jonathan']), // html view
            200 // status code
        );
        return $response;
    }

}