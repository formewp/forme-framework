<?php
declare(strict_types=1);

namespace Forme\Framework\Router\Strategy;

use DI\FactoryInterface;
use Forme\Framework\Http\Handlers\AjaxHandler;
use Forme\Framework\Http\Handlers\HandlerInterface;
use InvalidArgumentException;

final class AjaxPublicStrategy implements StrategyInterface
{
    public function __construct(private FactoryInterface $container)
    {
    }

    public function convert(string $route, callable $handler, ?string $method): HandlerInterface
    {
        if ($method && $method !== 'POST') {
            throw new InvalidArgumentException('Ajax routes can only be POST');
        }

        /** @var AjaxHandler */
        $ajaxHandler = $this->container->make(AjaxHandler::class);
        $ajaxHandler->setHandler($handler);

        add_action('wp_ajax_nopriv_' . $route, $ajaxHandler);

        return $ajaxHandler;
    }
}
