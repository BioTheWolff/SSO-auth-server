<?php declare(strict_types=1);

namespace App\Middleware;

use App\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use function give_render;

/**
 * This middleware protects the whole site with a login requirement.
 * 
 */
class AdminMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {

        /**
         * Only allow processing if the user is admin
         */

        if(Session::is_connected() && Session::is_user_admin()) {
            return $handler->handle($request);
        }

        // If one of the above is false, we display a 403 error
        return new HtmlResponse(
            give_render('http_error', ['error' => '403 Forbidden']),
            403
        );
    }
}