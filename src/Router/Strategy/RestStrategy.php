<?php
declare(strict_types=1);

namespace Forme\Framework\Router\Strategy;

use DI\FactoryInterface;
use Forme\Framework\Http\Handlers\HandlerInterface;
use Forme\Framework\Http\Handlers\RestHandler;
use InvalidArgumentException;

final class RestStrategy implements StrategyInterface
{
    /** @var FactoryInterface */
    private $container;

    public function __construct(FactoryInterface $container)
    {
        $this->container = $container;
    }

    public function convert(string $route, callable $handler, ?string $method): HandlerInterface
    {
        if ($method && !in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            throw new InvalidArgumentException('Invalid REST Route Method');
        }
        /** @var RestHandler */
        $restHandler = $this->container->make(RestHandler::class);
        $restHandler->setHandler($handler);
        add_action('rest_api_init', function () use ($method, $restHandler, $route) {
            $routeBase = substr($route, strpos($route, '/', 1));
            $namespace = str_replace($routeBase, '', $route);
            register_rest_route($namespace, $routeBase, [
                'methods'             => $method ?? 'GET',
                'callback'            => $restHandler,
                'permission_callback' => '__return_true',
            ]);
        });

        return $restHandler;
    }
}
