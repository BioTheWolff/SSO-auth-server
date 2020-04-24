<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Firebase\JWT\JWT;

class SSOController {

    public function givePubkey(ServerRequestInterface $request) : ResponseInterface {
        return new TextResponse(ECDSA_PUBKEY);
    }

    public function handleRedirect(ServerRequestInterface $request) : ResponseInterface {
        $query = $request->getUri()->getQuery();

        $res = \look_up_param($query, "url");
        if ($res === null) return new HtmlResponse(\give_render('sso/fail_no_url'), 400);
        

        $response = new Response;
        $response->getBody()->write('<h1>Test !</h1>');
        return $response;
    }

}