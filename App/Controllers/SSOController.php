<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
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
        

        $payload = array(
            "iss" => AUTH_SERVER_HOSTNAME,
            "aud" => $res,
            "iat" => time(),
            "uid" => \App\Session::get_user_value('id'),
            "uname" => \App\Session::get_user_value('username'),
            "mail" => \App\Session::get_user_value('email'),
        );
        
        $jwt = JWT::encode($payload, ECDSA_PVTKEY, 'RS256');

        return new RedirectResponse('http://' . $res, 302, ['Authorization' => "Bearer $jwt"]);
    }

}