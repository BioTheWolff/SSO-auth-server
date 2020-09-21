<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use App\Database;
use App\Session;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use function give_render;
use function password_verify;
use function reconstruct_path_from_redirect_param;

class LoginController {

    // Index page (once connected)
    public function index(ServerRequestInterface  $request) : ResponseInterface {
        return new HtmlResponse(
            give_render('index'),
            200
        );
    }

    // Login page in GET
    public function renderLoginForm(ServerRequestInterface $request) : ResponseInterface {
        // If the user is already connected, handle the request instead of asking to connect!
        if (Session::is_connected()) return self::delegate_handle_uri_params($request->getUri()->getQuery());

        return self::render_login_page();
    }

    // Logout
    public function logout(ServerRequestInterface $request) : ResponseInterface {
        Session::disconnect();
        return new RedirectResponse(USER_LOGIN);
    }

    // Login page in POST
    public function verifyLogin(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // Try to get the email and password from the form
        if (empty($form['email']) || empty($form['pass'])) {
            return self::render_login_page(ERROR_FILL_ALL_FIELDS);
        }

        // Database query
        [$query, $res] = self::delegate_verify_in_database($form['email'], $form['pass']);
        if ($res !== null) return $res;
        
        // Everything went well, we register the session and log the user in
        Session::populate_user_session($query->id, $query->username, $query->email, $query->admin);

        return self::delegate_handle_uri_params($request->getUri()->getQuery());
    }

    // Helpers below
    private function render_login_page($error = '') {

        return new HtmlResponse(
            give_render('login', ['error' => $error]),
            200
        );
    }

    private function delegate_verify_in_database($email, $pass) {
        $query = Database::getUserWithEither($email);

        if ($query === false) {
            // Query returned false, there was an error during database communication
            return [$query, self::render_login_page(ERROR_DATABASE)];
        }

        if (empty($query)) {
            // Query returned empty array, no matching email in database
            return [$query, self::render_login_page(ERROR_CREDENTIALS)];
        }

        if (!password_verify($pass, $query->password)) {
            // Given password and hash stored in database don't match
            return [$query, self::render_login_page(ERROR_CREDENTIALS)];
        }

        return [$query, null];
    }

    private function delegate_handle_uri_params($query) {

        [$res, $had_to_fallback] = reconstruct_path_from_redirect_param($query, '/', true);

        if ($had_to_fallback) Session::flash_message(
            'error',
            "The redirect query could not be successfully processed. You were redirected to your profile instead.<br> Query: $query"
        );

        return new RedirectResponse($res);

    }

}