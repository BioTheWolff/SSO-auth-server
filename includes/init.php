<?php 
define('ROOT', dirname(__DIR__) . '/');
require_once(ROOT . 'vendor/autoload.php');
require_once('config.php');
require_once('routes.php');

App\Session::init();