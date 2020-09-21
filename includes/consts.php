<?php

// ERRORS

define('ERROR_FILL_ALL_FIELDS', 'You must fill in the two fields');
define('ERROR_DATABASE', 'An error occurred during database communication. If the problem reoccurs, please try again later.');
define('ERROR_CREDENTIALS', 'Incorrect email or password.');

define('NOT_AVAILABLE_EMAIL', 'The requested email is not available.');
define('NOT_AVAILABLE_USERNAME', 'The requested username is not available.');

define('INVALID_EMAIL', 'Invalid email.');
define('INVALID_USERNAME', 'Usernames can only contain letters (a-z and A-Z), figures (0-9), dots (.), dashes (-) or underscores (_)');

define('ERROR_PASSWORD', 'Incorrect password.');
define('NEW_PASSWORDS_DONT_MATCH', 'New passwords do not match. Please retry');

// ROUTES

define('USER_LOGIN', '/login');
define('USER_LOGOUT', '/logout');

define('ADMIN_PART', '/admin');

define('SSO_AUTH', '/auth');
define('SSO_PUBKEY', '/pubkey');
define('SSO_VERIFY', '/verify');