<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use App\Database;
use App\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use function give_render;

class AdminController {

    /**
     * Admin panel page
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     */
    public function adminPanel(ServerRequestInterface $request) : ResponseInterface {
        $query = Database::getAllUsersInfo();

        return new HtmlResponse(give_render('admin/panel', ['users' => $query]));
    }

    /**
     * Create a new user (GET)
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     */
    public function newUser(ServerRequestInterface $request) : ResponseInterface {
        return self::render_form();
    }

    /**
     * Create a new user (POST)
     * @param ServerRequestInterface $request
     * @return HtmlResponse|RedirectResponse
     */
    public function handleNewUser(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // Tests
        $tests = self::delegate_verify($form['email'], $form['username'], $form['pass']);
        if ($tests !== null) return $tests;
        

        // Database insert
        $res = Database::createUser($form['email'], $form['username'], $form['pass']);

        if ($res === false) return new HtmlResponse(give_render('admin/new_user', ['error' => ERROR_DATABASE]));
        
        // Everything went well
        Session::flash_message('success', 'New user with username ' . e($form['username']) . ' was successfully created !');

        return new RedirectResponse(ADMIN_PART . '/panel');
    }

    // Helpers

    /**
     * Render the form with errors
     * @param string $error
     * @return HtmlResponse
     */
    private function render_form(String $error = '') : HtmlResponse {
        return new HtmlResponse(give_render('admin/new_user', ['error' => $error]));
    }

    /**
     * Verifies that you can create a new user under this name and email
     * @param $email
     * @param $username
     * @param $password
     * @return HtmlResponse|null
     */
    private function delegate_verify($email, $username, $password) {
        if (empty($email) || empty($username) || empty($password)) {
            return new HtmlResponse(give_render('admin/new_user', ['error' => 'You must fill in all the fields']));
        }

        $username_pattern = '/[^a-aA-Z0-9.\-_]/mi';
        
        // email
        $is_email_available = Database::getUserWithEmail($email);

        if (!empty($is_email_available)) {
            return self::render_form(NOT_AVAILABLE_EMAIL);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return self::render_form(INVALID_EMAIL);
        }

        // username
        if (!empty($is_username_available)) {
            return self::render_form(NOT_AVAILABLE_USERNAME);
        }
        if (preg_match($username_pattern, $username)) {
            return self::render_form(INVALID_USERNAME);
        }

        // everything is good
        return null;
    }

}