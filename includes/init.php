<?php 
define('ROOT', dirname(__DIR__) . '/');
require_once(ROOT . 'vendor/autoload.php');
require_once('config.php');

App\Session::init();