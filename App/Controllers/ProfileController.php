<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class ProfileController {

    private function render_profile_page(bool $is_edit, string $error = '') {
        $templates = new \League\Plates\Engine(dirname(__DIR__) . '/../templates/');

        $display_path = $is_edit ? 'profile/edit' : 'profile/display';

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            $templates->render($display_path, [
                'username' => \App\Session::get_user_value('username'),
                'email' => \App\Session::get_user_value('email'),
                'error' => $error
                ]),
            200
        );
        return $response;
    }


    public function redirectToProfile(ServerRequestInterface $request) : ResponseInterface {
        return new RedirectResponse('/profile');
    }

    /**
     * DISPLAYS
     */
    // Display profile
    public function getProfile(ServerRequestInterface $request) : ResponseInterface {
        return self::render_profile_page(false);
    }

    // Display profile edition
    public function editProfile(ServerRequestInterface $request) : ResponseInterface {
        return self::render_profile_page(true);
    }

    /**
     * HANDLERS
     */
    // Handle profile edition
    public function handleEditProfile(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // We take form values
        $email = $form['email'];
        $username = $form['username'];
        $password = $form['pass'];

        // "old" values taken from session
        $id = \App\Session::get_user_value('id');
        $old_email = \App\Session::get_user_value('email');
        $old_username = \App\Session::get_user_value('username');
        

        /**
         * TESTS
         */
        // we test email, if it has changed
        if ($old_email != $email) {
            $is_email_available = \App\Database::getUserWithEmail($email);
            if (!empty($is_email_available)) {
                return self::render_profile_page(true, NOT_AVAILABLE_EMAIL);
            }
        }

        // we test username if it has changed
        if ($old_username != $username) {
            $is_username_available = \App\Database::getUserWithUsername($username);
            if (!empty($is_username_available)) {
                return self::render_profile_page(true, NOT_AVAILABLE_USERNAME);
            }
        }

        /**
         * PASSWORD VERIFICATION
         */
        $query = \App\Database::getUserFromFull($id, $old_email, $old_username);

        if ($query === false || empty($query)) {
            return self::render_profile_page(true, ERROR_DATABASE);
        }

        if (!\password_verify($password, $query->password)) {
            return self::render_profile_page(true, ERROR_PASSWORD);
        }

        /**
         * USER UPDATE
         */
        $update = \App\Database::updateUserProfile($id, $email, $username);

        if ($update === false) {
            return self::render_profile_page(true, ERROR_DATABASE);
        }

        // update session
        \App\Session::populate_user_session($id, $username, $email);

        // flash success message and redirect
        \App\Session::flash_message('success', 'Profile successfully updated!');
        return new RedirectResponse('/profile');

    }

}