<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RedirectController {

    public function handle(ServerRequestInterface $request) : ResponseInterface {
        $response = new Laminas\Diactoros\Response;
        $response->getBody()->write('<h1>Redirect !</h1>');
        return $response;
    }

}