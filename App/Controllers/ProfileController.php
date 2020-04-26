<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class ProfileController {

    private function render_profile_page(bool $is_edit, bool $is_pass_change = false, string $error = '') {

        if ($is_edit && $is_pass_change) $display_path = 'profile/change_password';
        else if ($is_edit && !$is_pass_change) $display_path = 'profile/edit';
        else $display_path = 'profile/display';

        $response = new \Laminas\Diactoros\Response\HtmlResponse(
            \give_render($display_path, [
                'username' => \App\Session::get_user_value('username'),
                'email' => \App\Session::get_user_value('email'),
                'error' => $error
                ]),
            200
        );
        return $response;
    }


    public function redirectToProfile(ServerRequestInterface $request) : ResponseInterface {
        return new RedirectResponse(USER_PROFILE);
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

    // Display password change
    public function changePassword(ServerRequestInterface $request) : ResponseInterface {
        return self::render_profile_page(true, true);
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

        // valid pattern
        $username_pattern = '/[^a-aA-Z0-9.\-_]/mi';
        

        /**
         * TESTS
         */
        // we test email, if it has changed
        if ($old_email != $email) {
            $is_email_available = \App\Database::getUserWithEmail($email);
            if (!empty($is_email_available)) {
                return self::render_profile_page(true, false, NOT_AVAILABLE_EMAIL);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return self::render_profile_page(true, false, INVALID_EMAIL);
            }
        }

        // we test username if it has changed
        if ($old_username != $username) {
            $is_username_available = \App\Database::getUserWithUsername($username);
            if (!empty($is_username_available)) {
                return self::render_profile_page(true, false, NOT_AVAILABLE_USERNAME);
            }
            if (preg_match($username_pattern, $username)) {
                return self::render_profile_page(true, false, INVALID_USERNAME);
            }
        }

        /**
         * PASSWORD VERIFICATION
         */
        $query = \App\Session::db_result_for_user_details();

        if ($query === false || empty($query)) {
            return self::render_profile_page(true, false, ERROR_DATABASE);
        }

        if (!\password_verify($password, $query->password)) {
            return self::render_profile_page(true, false, ERROR_PASSWORD);
        }

        /**
         * USER UPDATE
         */
        $update = \App\Database::updateUserProfile($id, $email, $username);

        if ($update === false) {
            return self::render_profile_page(true, false, ERROR_DATABASE);
        }

        // update session
        \App\Session::populate_user_session($id, $username, $email);

        // flash success message and redirect
        \App\Session::flash_message('success', 'Profile successfully updated!');
        return new RedirectResponse('/profile');

    }

    public function handleChangePassword(ServerRequestInterface $request) : ResponseInterface {
        $form = $request->getParsedBody();

        // We take form values
        $old = $form['current'];
        $new = $form['new'];
        $confirm = $form['confirm_new'];

        $query = \App\Session::db_result_for_user_details();

        /**
         * VERIFICATION
         */
        // If current password is not right
        if (!\password_verify($old, $query->password)) {
            return self::render_profile_page(true, true, ERROR_PASSWORD);
        }

        // if new password and confirmation match
        if ($new != $confirm) {
            return self::render_profile_page(true, true, NEW_PASSWORDS_DONT_MATCH);
        }

        /**
         * UPDATE
         */
        $update = \App\Database::updateUserPassword(\App\Session::get_user_value('id'), $new);

        if ($update === false) {
            return self::render_profile_page(true, true, ERROR_DATABASE);
        }

        // finalise
        \App\Session::flash_message('success', 'Password successfully updated!');
        return new RedirectResponse('/user/profile');
    }

}