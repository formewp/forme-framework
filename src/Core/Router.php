<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use Exception;
use Forme\Framework\Http\Handlers\HandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

final class Router
{
    /** @var array */
    private static $test = [];

    /** @var HandlerInterface */
    private static $currentHandler;

    /**
     * Map the route onto the relevant wp route type.
     *
     * @param string|callable $handler
     */
    public static function map(string $route, $handler, string $type = 'custom', ?string $method = null): static
    {
        $container = \Forme\getContainer();
        $strategy  = ($container->get('Forme\Framework\Router\Strategy\StrategyFactory'))->get($type);
        // if the handler is a class we should get it via the container otherwise it won't autowire
        if (is_string($handler) && class_exists($handler)) {
            if (method_exists($handler, 'handle')) {
                // if this has a handle method then use that
                $handler = [$handler, 'handle'];
            } else {
                // otherwise hope the best for __invoke
                $handler = $container->get($handler);
            }
        }
        if (is_array($handler) && class_exists($handler[0])) {
            $handler[0] = $container->get($handler[0]);
        }

        if (!is_callable($handler)) {
            throw new Exception('Not a callable handler');
        }
        static::$currentHandler = $strategy->convert($route, $handler, $method);

        return new static();
    }

    /**
     * @param string|callable $handler
     */
    public static function get(string $route, $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'GET');
    }

    /**
     * @param string|callable $handler
     */
    public static function post(string $route, $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'POST');
    }

    /**
     * @param string|callable $handler
     */
    public static function put(string $route, $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'PUT');
    }

    /**
     * @param string|callable $handler
     */
    public static function patch(string $route, $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'PATCH');
    }

    /**
     * @param string|callable $handler
     */
    public static function delete(string $route, $handler, string $type = 'custom'): static
    {
        return self::map($route, $handler, $type, 'DELETE');
    }

    /**
     * @param string|MiddlewareInterface $value
     */
    public static function addMiddleware($value): static
    {
        static::$test[] = $value;
        static::$currentHandler->addMiddleware($value);

        return new static();
    }
}
