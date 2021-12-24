<?php

declare(strict_types=1);

namespace Forme\Framework\Core;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use Symfony\Component\Yaml\Yaml;

class Loader
{
    /**
     * @var array
     */
    private $actions = [];

    /**
     * @var array
     */
    private $filters = [];

    /** @var ContainerInterface */
    private $container;

    public function __construct()
    {
        $this->container = \Forme\getContainer();
    }

    /**
     * Adds an action to be registered with WordPress.
     */
    public function addAction(string $actionName, string $class, string $method, int $priority = 10, int $numberOfArgs = 1): void
    {
        $this->actions[] = $this->convert($actionName, $class, $method, $priority, $numberOfArgs);
    }

    /**
     * Adds a filter to be registered with WordPress.
     */
    public function addFilter(string $filterName, string $class, string $method, int $priority = 10, int $numberOfArgs = 1): void
    {
        $this->filters[] = $this->convert($filterName, $class, $method, $priority, $numberOfArgs);
    }

    /**
     * Register all the actions and filters within a suitably formatted config yaml.
     */
    public function addConfig(string $yaml): void
    {
        $config = Yaml::parse($yaml);
        if (isset($config['actions'])) {
            foreach ($config['actions'] as $action) {
                $method    = isset($action['method']) ? $action['method'] : 'default';
                $priority  = isset($action['priority']) ? $action['priority'] : 10;
                $arguments = isset($action['arguments']) ? $action['arguments'] : 1;
                $this->addAction($action['hook'], $action['class'], $method, $priority, $arguments);
            }
        }
        if (isset($config['filters'])) {
            foreach ($config['filters'] as $filter) {
                $method    = isset($filter['method']) ? $filter['method'] : 'default';
                $priority  = isset($filter['priority']) ? $filter['priority'] : 10;
                $arguments = isset($filter['arguments']) ? $filter['arguments'] : 1;
                $this->addFilter($filter['hook'], $filter['class'], $method, $priority, $arguments);
            }
        }
    }

    private function convert(string $hook, string $class, string $method, int $priority, int $numberOfArgs): array
    {
        if ($method === 'default') {
            // for backwards compatibility we need to check if the the class has register method
            $rc     = new ReflectionClass($class);
            $method = $rc->hasMethod('register') ? 'register' : '__invoke';
        }

        return [
            'hook'                  => $hook,
            'resolvedCallable'      => $method === '__invoke' ? $this->container->get($class) : [$this->container->get($class), $method],
            'priority'              => $priority,
            'numberOfArgs'          => $numberOfArgs,
        ];
    }

    /**
     * Register user and core hooks with WordPress.
     */
    public function run(): void
    {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], $hook['resolvedCallable'], $hook['priority'], $hook['numberOfArgs']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], $hook['resolvedCallable'], $hook['priority'], $hook['numberOfArgs']);
        }

        $coreHooks = $this->container->get(CoreHooks::class);
        $coreHooks->load();
    }
}
