<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class LoginController {

    private function render_login_page($error = '') {
        $templates = new \League\Plates\Engine(dirname(__DIR__) . '/../templates/');

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            $templates->render('login', ['error' => $error]),
            200
        );
        return $response;
    }

    public function renderLoginForm(ServerRequestInterface $request) : ResponseInterface {
        return self::render_login_page();
    }

    public function logout(ServerRequestInterface $request) : ResponseInterface {
        \App\Session::disconnect();
        return new RedirectResponse('/login');
    }

    public function verifyLogin(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // Try to get the email and password from the form
        if (empty($form['email']) || empty($form['pass'])) {
            return self::render_login_page('You must fill in the two fields');
        }

        // Database query
        $query = \App\Database::getUserWithEmail($form['email']);

        if ($query === false) {
            // Query returned false, there was an error during database communication
            return self::render_login_page('An error occurred during database communication. If the problem reoccurs, please try again later');
        }

        if (empty($query)) {
            // Query returned empty array, no matching email in database
            return self::render_login_page('Email or password incorrect');
        }

        if (!\password_verify($form['pass'], $query->password)) {
            // Given password and hash stored in database don't match
            return self::render_login_page('Email or password incorrect');
        }
        
        // Everything went well, we register the session and log the user in
        $_SESSION['__user'] = array(
            'id' => $query->id,
            'email' => $query->email,
            'username' => $query->username
        );

        return new RedirectResponse('/profile');
    }

}