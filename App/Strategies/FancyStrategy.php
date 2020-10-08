<?php declare(strict_types=1);

namespace App\Strategies;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Plates\Engine;
use League\Route\Http\Exception\{MethodNotAllowedException, NotFoundException};
use League\Route\Route;
use League\Route\Strategy\AbstractStrategy;
use League\Route\{ContainerAwareInterface, ContainerAwareTrait};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Throwable;


/**
 * This strategy aims to provide smooth error handling by displaying an appropriate response to the end user.
 */
class FancyStrategy extends AbstractStrategy implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $controller = $route->getCallable($this->getContainer());

        $response = $controller($request, $route->getVars());
        $response = $this->applyDefaultResponseHeaders($response);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return $this->returnFancyErrorMiddleware(404, '404 Not Found');
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return $this->returnFancyErrorMiddleware(405, '405 Method Not Allowed');
    }

    /**
     * Return a middleware that simply throws an error
     *
     * @param Int $code
     * @param String $title
     * @return MiddlewareInterface
     */
    protected function returnFancyErrorMiddleware(Int $code, String $title): MiddlewareInterface
    {
        return new class($code, $title) implements MiddlewareInterface
        {
            protected $code;
            protected $title;

            public function __construct(Int $code, String $title)
            {
                $this->code = $code;
                $this->title = $title;
            }

            public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface {
                $templates = new Engine(dirname(__DIR__) . '/../templates/');

                return new HtmlResponse(
                    $templates->render('http_error', ['title' => $this->title]),
                    (int)$this->code
                );
            }
        };
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptionHandler(): MiddlewareInterface
    {
        return $this->getThrowableHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function getThrowableHandler(): MiddlewareInterface
    {
        return new class implements MiddlewareInterface
        {
            /**
             * {@inheritdoc}
             *
             * @throws Throwable
             */
            public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface {
                try {
                    return $requestHandler->handle($request);
                } catch (Throwable $e) {
                    throw $e;
                }
            }
        }; 
    }
}