<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

interface ControllerInterface
{
    /**
     * @param array|ServerRequestInterface $request
     *
     * @return mixed
     *
     * this method should send back relevant response to output
     * most often a string or an http response
     */
    public function handle($request);

    public function addMiddleware(MiddlewareInterface|string $middleware): void;

    public function getMiddlewareQueue(): array;
}
