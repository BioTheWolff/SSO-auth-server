<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;

class AdminController {

    public function adminPanel(ServerRequestInterface $request) : ResponseInterface {
        $query = \App\Database::getAllUsersInfo();

        return new HtmlResponse(\give_render('admin/panel', ['users' => $query]));
    }

    public function newUser(ServerRequestInterface $request) : ResponseInterface {
        return new HtmlResponse(\give_render('admin/new_user', ['error' => '']));
    }

    public function handleNewUser(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // Try to get the email and password from the form
        if (empty($form['email']) || empty($form['username']) || empty($form['pass'])) {
            return new HtmlResponse(\give_render('admin/new_user', ['error' => 'You must fill in all the fields']));
        }

        // Database insert
        $res = \App\Database::createUser($form['email'], $form['username'], $form['pass']);

        if ($res === false) return new HtmlResponse(\give_render('admin/new_user', ['error' => ERROR_DATABASE]));
        
        // Everything went well
        \App\Session::flash_message('success', 'New user with username ' . e($form['username']) . ' was successfully created !');

        return new RedirectResponse(ADMIN_PART . '/panel');
    }

}