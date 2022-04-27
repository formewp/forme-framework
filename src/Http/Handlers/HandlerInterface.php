<?php
declare(strict_types=1);

namespace Forme\Framework\Http\Handlers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

interface HandlerInterface
{
    public function addMiddleware(MiddlewareInterface|string $middleware): void;

    public function dispatchMiddleware(RequestInterface $request, callable $responseFunc): ResponseInterface;
}
