<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class MainController {

    private function render_profile_page() {
        $templates = new \League\Plates\Engine(dirname(__DIR__) . '/../templates/');

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            $templates->render('profile', ['username' => \App\Session::get_user_value('username')]),
            200
        );
        return $response;
    }


    public function redirectToProfile(ServerRequestInterface $request) : ResponseInterface {
        return new RedirectResponse('/profile');
    }

    public function getProfile(ServerRequestInterface $request) : ResponseInterface {
        return self::render_profile_page();
    }

}