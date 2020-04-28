<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;

class AdminController {

    private function render_form(String $error = '') : HtmlResponse {
        return new HtmlResponse(\give_render('admin/new_user', ['error' => $error]));
    }

    public function adminPanel(ServerRequestInterface $request) : ResponseInterface {
        $query = \App\Database::getAllUsersInfo();

        return new HtmlResponse(\give_render('admin/panel', ['users' => $query]));
    }

    public function newUser(ServerRequestInterface $request) : ResponseInterface {
        return self::render_form();
    }

    public function handleNewUser(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // Tests
        $tests = self::delegate_verify($form['email'], $form['username'], $form['pass']);
        if ($tests !== null) return $tests;
        

        // Database insert
        $res = \App\Database::createUser($form['email'], $form['username'], $form['pass']);

        if ($res === false) return new HtmlResponse(\give_render('admin/new_user', ['error' => ERROR_DATABASE]));
        
        // Everything went well
        \App\Session::flash_message('success', 'New user with username ' . e($form['username']) . ' was successfully created !');

        return new RedirectResponse(ADMIN_PART . '/panel');
    }

    private function delegate_verify($email, $username, $password) {
        if (empty($email) || empty($username) || empty($password)) {
            return new HtmlResponse(\give_render('admin/new_user', ['error' => 'You must fill in all the fields']));
        }

        $username_pattern = '/[^a-aA-Z0-9.\-_]/mi';
        
        // email
        $is_email_available = \App\Database::getUserWithEmail($email);

        if (!empty($is_email_available)) {
            return self::render_form(NOT_AVAILABLE_EMAIL);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return self::render_form(INVALID_EMAIL);
        }

        // username
        if ($old_username != $username) {
            $is_username_available = \App\Database::getUserWithUsername($username);
            if (!empty($is_username_available)) {
                return self::render_form(NOT_AVAILABLE_USERNAME);
            }
            if (preg_match($username_pattern, $username)) {
                return self::render_form(INVALID_USERNAME);
            }
        }

        // everything is good
        return null;
    }

}