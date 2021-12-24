<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

interface HandlerInterface
{
    /**
     * @param MiddlewareInterface|string $middleware
     */
    public function addMiddleware($middleware);

    public function dispatchMiddleware(RequestInterface $request, callable $responseFunc): ResponseInterface;
}
