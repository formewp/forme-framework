<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Relay\Relay;

trait HasMiddleware
{
    /** @var array */
    protected $middlewareQueue = [];

    public function addMiddleware(MiddlewareInterface|string $middleware): void
    {
        $this->middlewareQueue[] = $middleware;
    }

    public function getMiddlewareQueue(): array
    {
        return $this->middlewareQueue;
    }

    public function addMiddlewareQueue(array $queue): void
    {
        $this->middlewareQueue = array_merge($this->middlewareQueue, $queue);
    }

    public function dispatchMiddleware(ServerRequestInterface $request, callable $responseFunc): ResponseInterface
    {
        $queue    = array_merge($this->middlewareQueue ?: [], [$responseFunc]);
        $resolver = fn ($entry) => is_string($entry) ? \Forme\getInstance($entry) : $entry;
        $relay = new Relay($queue, $resolver);

        return $relay->handle($request);
    }
}
