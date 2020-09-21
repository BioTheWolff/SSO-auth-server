<?php declare(strict_types=1);

namespace App\Middleware;

use App\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use function explode;
use function implode;
use function in_array;

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
        $public_paths = array(USER_LOGIN, CAS_PUBKEY, CAS_VERIFY);

        if(Session::is_connected() || in_array($uri->getPath(), $public_paths)) {
            // Handle the request
            return $handler->handle($request);
        }

        /**
         * If the user is not connected and the path is not public
         */
        // compile the uri and put it in parameters
        if ($uri->getQuery() == '') {
            $params = '';
        } else if (strpos($uri->getQuery(), '?') !== false) {
            $arr = explode("?", $uri->getQuery());
            $params = '?redirect=' . implode("&", $arr);
        } else {
            $params = '?redirect=' . $uri->getQuery();
        }

        // we redirect the user to the login page (which will be handled above)
        return new RedirectResponse(USER_LOGIN . $params);
    }
}