<?php
declare(strict_types=1);

namespace Forme\Framework\Router\Strategy;

use Exception;
use Psr\Container\ContainerInterface;
use function Symfony\Component\String\u;

class StrategyFactory
{
    public const TYPES = ['ajax-public', 'ajax-private', 'rest', 'custom'];

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get(string $type): StrategyInterface
    {
        if (in_array($type, self::TYPES)) {
            $class = __NAMESPACE__ . '\\' . u($type)->camel()->title()->toString() . 'Strategy';

            return $this->container->get($class);
        } else {
            throw new Exception('Undefined routing type');
        }
    }
}
