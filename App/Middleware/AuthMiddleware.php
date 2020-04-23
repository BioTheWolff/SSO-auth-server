<?php declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;

/**
 * This middleware protects the whole site with a login requirement.
 * 
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {

        $uri = $request->getUri();
        /**
         * If the user is connection ($_SESSION['__user'] is not null)
         * or if the URI path is considered public (see array)
         */
        $public_paths = array('/login', '/sso/pubkey');

        if(\App\Session::is_connected() || \in_array($uri->getPath(), $public_paths)) {
            return $handler->handle($request);
        }

        // compile the uri and put it in parameters
        if ($uri->getQuery() == '') {
            $params = '';
        } else if (strpos($uri->getQuery(), '?') !== false) {
            $arr = \explode("?", $uri->getQuery());
            $params = '?redirect=' . \implode("&", $arr);
        } else {
            $params = '?redirect=' . $uri->getQuery();
        }

        // If neither of above is true, we redirect the user to the login page (which will be handled above)
        return new RedirectResponse('/login' . $params);
    }
}