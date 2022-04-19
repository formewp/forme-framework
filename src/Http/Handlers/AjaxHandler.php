<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use Forme\Framework\Http\ResponseFactory;
use Forme\Framework\Http\Shutdown;

class AjaxHandler implements HandlerInterface
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

    public function __invoke(): void
    {
        $request     = \Forme\request();

        $controller = $this->handler;

        // we pass the response closure into the dispatcher
        $responseFunc = function ($request, $handler) use ($controller) {
            $response    = call_user_func($controller, $request);

            return ResponseFactory::create($response);
        };
        $response       = $this->dispatchMiddleware($request, $responseFunc);
        $this->shutdown->shutdown($response);
    }
}
