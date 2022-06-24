<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use Exception;
use Forme\Framework\Http\Handlers\HandlerInterface;
use Forme\Framework\Router\Strategy\StrategyFactory;
use function Forme\getContainer;
use Psr\Http\Server\MiddlewareInterface;

final class Router
{
    /** @var HandlerInterface */
    private static $currentHandler;

    /**
     * Map the route onto the relevant wp route type.
     */
    public static function map(string $route, callable|string|array $handler, string $type = 'custom', ?string $method = null): static
    {
        $container = getContainer();
        $strategy  = ($container->get(StrategyFactory::class))->get($type);
        // if the handler is a class we should get it via the container otherwise it won't autowire
        if (is_string($handler) && class_exists($handler)) {
            $handler = method_exists($handler, 'handle') ? [$handler, 'handle'] : $container->get($handler);
        }

        if (is_array($handler) && class_exists($handler[0])) {
            $handler[0] = $container->get($handler[0]);
        }

        if (!is_callable($handler)) {
            throw new Exception('Not a callable handler');
        }

        static::$currentHandler = $strategy->convert($route, $handler, $method);

        return new self();
    }

    public static function get(string $route, callable|string|array $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'GET');
    }

    public static function post(string $route, callable|string|array $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'POST');
    }

    public static function put(string $route, callable|string|array $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'PUT');
    }

    public static function patch(string $route, callable|string|array $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'PATCH');
    }

    public static function delete(string $route, callable|string|array $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'DELETE');
    }

    public static function addMiddleware(MiddlewareInterface|string $value): static
    {
        static::$currentHandler->addMiddleware($value);

        return new self();
    }
}
