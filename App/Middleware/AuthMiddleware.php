<?php declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // We check the user has a session

        $query = $request->getQueryParams();
        $first = array_keys($query)[0];

        var_dump($first);
        var_dump(preg_match('/^\/login/gmi', '/login'));

        if(\App\Session::is_connected() || preg_match('/^\/login/gmi', $first)) {
            echo 'TRUE !';
        }

        return $handler->handle($request);

        // return new RedirectResponse('/login');
    }
}