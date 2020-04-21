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
        // get query params
        $query = $request->getQueryParams();

        // if params are not empty, try to match /login
        if (!empty($query)) {
            $first = array_keys($query)[0];
            $uri_matches = preg_match('/^\/login/', $first);
        } else {
            $uri_matches = false;
        }

        /**
         * If the user is connection ($_SESSION['__user'] is not null)
         * or if the URI starts by /login (the user wants to login)
         */

        if(\App\Session::is_connected() || $uri_matches !== false) {
            return $handler->handle($request);
        }

        // If neither of above is true, we redirect the user to the login page (which will be handled because of the uri_matches above)
        return new RedirectResponse('/login');
    }
}