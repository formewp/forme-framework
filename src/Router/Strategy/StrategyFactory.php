<?php
declare(strict_types=1);

namespace Forme\Framework\Router\Strategy;

use Psr\Container\ContainerInterface;
use function Symfony\Component\String\u;

class StrategyFactory
{
    /**
     * @var string[]
     */
    public const TYPES = ['ajax-public', 'ajax-private', 'rest', 'custom'];

    public function __construct(private ContainerInterface $container)
    {
    }

    public function get(string $type): StrategyInterface
    {
        if (in_array($type, self::TYPES)) {
            $class = __NAMESPACE__ . '\\' . u($type)->camel()->title()->toString() . 'Strategy';

            return $this->container->get($class);
        } else {
            throw new StrategyException('Undefined routing type');
        }
    }
}
