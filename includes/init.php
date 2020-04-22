<?php 
define('ROOT', dirname(__DIR__) . '/');
require_once(ROOT . 'vendor/autoload.php');
require_once('config.php');

define('ERROR_DATABASE', 'An error occurred during database communication. If the problem reoccurs, please try again later');
define('ERROR_CREDENTIALS', 'Incorrect email or password');

define('NOT_AVAILABLE_EMAIL', 'The requested email is not available');
define('NOT_AVAILABLE_USERNAME', 'The requested username is not available');
define('ERROR_PASSWORD', 'Incorrect password');

App\Session::init();