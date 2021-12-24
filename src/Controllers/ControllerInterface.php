<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers;

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

    /**
     * @param MiddlewareInterface|string $middleware
     */
    public function addMiddleware($middleware);

    public function getMiddlewareQueue(): array;
}
