<?php
declare(strict_types=1);

namespace Forme\Framework\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TestOnlyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var bool $testing */
        $testing = WP_ENV === 'testing';
        if (!$testing) {
            return new JsonResponse(['error' => 'Not Allowed outside of test environment'], 401);
        } else {
            return $handler->handle($request);
        }
    }
}
