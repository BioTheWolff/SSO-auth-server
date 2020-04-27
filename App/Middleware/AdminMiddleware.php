<?php declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Response\HtmlResponse;

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

        if(\App\Session::is_connected() && \App\Session::is_user_admin()) {
            return $handler->handle($request);
        }

        // If neither of above is true, we display a 403 error
        $response = new HtmlResponse(
            \give_render('http_error', ['error' => '403 Forbidden']),
            403
        );
        return $response;
    }
}