<?php
declare(strict_types=1);

namespace Forme\Framework\Router\Strategy;

use DI\FactoryInterface;
use Forme\Framework\Http\Handlers\CustomRouteHandler;
use Forme\Framework\Http\Handlers\HandlerInterface;
use InvalidArgumentException;

final class CustomStrategy implements StrategyInterface
{
    public function __construct(private FactoryInterface $container)
    {
    }

    public function convert(string $route, callable $handler, ?string $method): HandlerInterface
    {
        if ($method && !in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            throw new InvalidArgumentException('Invalid Custom Route Method');
        }

        /** @var CustomRouteHandler */
        $customRouteHandler = $this->container->make(CustomRouteHandler::class);
        $customRouteHandler->map($route, $handler, $method ?? 'GET');

        return $customRouteHandler;
    }
}
