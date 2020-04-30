<?php declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Uri;
use Firebase\JWT\JWT;

class SSOController {

    public function givePubkey(ServerRequestInterface $request) : ResponseInterface {
        return new TextResponse(PUBLIC_KEY);
    }

    public function handleRedirect(ServerRequestInterface $request) : ResponseInterface {
        $query = $request->getUri()->getQuery();

        $res = \look_up_param($query, "url");
        if ($res === null) return new HtmlResponse(\give_render('sso/fail_no_url'), 400);
        
        $return_uri = new Uri($res);

        $scheme = $return_uri->getScheme();
        $host = $return_uri->getHost();

        if (empty($scheme) || empty($host)) {
            return new HtmlResponse(\give_render('sso/fail_invalid_url'), 400);
        }

        if (\array_search($host, ACCEPTED_BROKERS) === false) {
            return new RedirectResponse($res . '?status=refused');
        }

        $payload = array(
            "iss" => AUTH_SERVER_HOSTNAME,
            "aud" => $res,
            "iat" => time(),
            "uid" => \App\Session::get_user_value('id'),
            "username" => \App\Session::get_user_value('username'),
            "email" => \App\Session::get_user_value('email'),
        );
        
        $jwt = JWT::encode($payload, PRIVATE_KEY, 'RS256');

        return new RedirectResponse($res . "?status=success&token=$jwt");
    }

}