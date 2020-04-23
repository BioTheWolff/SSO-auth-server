<?php

function give_render(String $file, array $params = []) {
    $templates = new \League\Plates\Engine(dirname(__DIR__) . '/templates/');
    return $templates->render($file, $params);
}