<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController {

    public function renderLoginForm(ServerRequestInterface $request) : ResponseInterface {
        $templates = new \League\Plates\Engine(dirname(__DIR__) . '/../templates/');

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            $templates->render('login'),
            200
        );
        return $response;
    }

    public function verifyLogin(ServerRequestInterface $request) : ResponseInterface {
        $templates = new \League\Plates\Engine(dirname(__DIR__) . '/../templates/');

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            $templates->render('profile', ['name' => 'Jonathan']), // html view
            200 // status code
        );
        return $response;
    }

}