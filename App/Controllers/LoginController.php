<?php declare(strict_types=1);

namespace App\Controllers;

require_once(dirname(__DIR__) . '/../includes/init.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class LoginController {

    private function render_login_page($error = '') {

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            \give_render('login', ['error' => $error]),
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
        [$query, $res] = self::delegate_verify_in_database($form['email'], $form['pass']);
        if ($res !== null) return $res;
        
        // Everything went well, we register the session and log the user in
        \App\Session::populate_user_session($query->id, $query->username, $query->email);

        return self::delegate_handle_uri_params($request->getUri()->getQuery());
    }

    private function delegate_verify_in_database($email, $pass) {
        $query = \App\Database::getUserWithEither($email);

        if ($query === false) {
            // Query returned false, there was an error during database communication
            return [$query, self::render_login_page(ERROR_DATABASE)];
        }

        if (empty($query)) {
            // Query returned empty array, no matching email in database
            return [$query, self::render_login_page(ERROR_CREDENTIALS)];
        }

        if (!\password_verify($pass, $query->password)) {
            // Given password and hash stored in database don't match
            return [$query, self::render_login_page(ERROR_CREDENTIALS)];
        }

        return [$query, null];
    }

    private function delegate_handle_uri_params($query) {
        try {
            // if query doesn't parameters
            if (\strpos($query, '?') === false) {
                $uri = '/profile';
                $params = '';

            // if query has parameters
            } else {
                [$e, $p] = explode('?', $query);

                // if we have more than one parameter (at least one & is present)
                if (\strpos($p, '&') !== false) {
                    $p_arr = explode('&', $p);

                    $save = false;
                    foreach ($p_arr as $par) {
                        // we loop through the code to check if a parameter has "redirect" as key
                        [$k, $v] = explode("=", $par);
                        if ($k == 'redirect') {
                            $save = true;
                            $uri = $v;
                            // the params will be reconstructed after we get out of the loop

                            // we unset the redirect value to be sure it is not reconstructed into the parameters
                            $key = \array_search($par, $p_arr);
                            unset($p_arr[$key]);
                            
                            break;
                        }
                    }
                    // throw error if no redirect parameter has been found
                    if (!$save) throw new \Exception("No redirect parameter but other parameters provided", 1);

                    // params reconstruction
                    $params = implode("&", $p_arr);
                    
                } else {
                    [$k, $v] = explode("=", $p);

                    if ($k != 'redirect') throw new \Exception("Only parameter is not the redirect parameter", 1);

                    $uri = $v;
                    $params = '';
                }
            }

            return new RedirectResponse($uri . '?' . $params);
        } catch (\Exception $e) {
            \App\Session::flash_message('error', 'An error happened during URI parsing to redirect upon logging in.
                                                    <br> Error: ' . $e->getMessage() . '<br>
                                                    For URI query: ' . $query);
            return new RedirectResponse('/profile');
        }
    }

}