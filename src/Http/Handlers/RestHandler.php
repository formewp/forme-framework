<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use Forme\Framework\Http\ResponseFactory;
use Forme\Framework\Http\Shutdown;
use Forme\Framework\Http\WPRest\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use WP_REST_Request;

class RestHandler implements HandlerInterface
{
    use HasMiddleware;

    /** @var callable */
    private $handler;

    public function __construct(private Shutdown $shutdown)
    {
    }

    public function setHandler(callable $handler): void
    {
        $this->handler = $handler;
    }

    public function __invoke(WP_REST_Request $request): ResponseInterface
    {
        // convert from WP_REST into PSR7
        $request = ServerRequest::fromRequest($request);

        $restHandler = $this->handler;

        // we pass the response closure into the dispatcher
        $responseFunc = function ($request, $handler) use ($restHandler) {
            $response   = call_user_func($restHandler, $request);

            return ResponseFactory::create($response);
        };
        $response       = $this->dispatchMiddleware($request, $responseFunc);

        $this->shutdown->shutdown($response);
    }
}
