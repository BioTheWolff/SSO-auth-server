<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class AdminController {

    public function adminPanel(ServerRequestInterface $request) : ResponseInterface {
        $query = \App\Database::getAllUsersInfo();

        return new HtmlResponse(\give_render('admin/panel', ['users' => $query]));
    }

}