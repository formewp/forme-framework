<?php
declare(strict_types=1);

namespace Forme\Framework\Jobs;

use ReflectionFunction;
use RuntimeException;

trait Queueable
{
    public function dispatch(array $args = []): void
    {
        $queue = $this->getQueue();
        $queue->dispatch(['class' => $this::class, 'arguments' => $args]);
    }

    public function schedule(array $args = []): void
    {
        $queue         = $this->getQueue();
        $args['class'] = $this::class;
        $queue->schedule($args);
    }

    public function start(array $args = []): void
    {
        $queue         = $this->getQueue();
        $args['class'] = $this::class;
        $queue->start($args);
    }

    public function stop(): void
    {
        $queue = $this->getQueue();
        $queue->stop(['class' => $this::class]);
    }

    protected function getQueue(): Queue
    {
        if (property_exists($this, 'queue') && is_a($this->queue, Queue::class)) {
            return $this->queue;
        }

        if (property_exists($this, 'container') && is_a($this->container, ContainerInterface::class)) {
            return $this->container->get(Queue::class);
        }

        if (function_exists('app')) {
            $reflection = new ReflectionFunction('app');
            $returnType = $reflection->getReturnType();
            if ($returnType && is_a((string) $returnType, ContainerInterface::class)) {
                return app()->get(Queue::class);
            }
        }

        if (function_exists('Forme\getInstance') && defined('FORME_PRIVATE_ROOT')) {
            return \Forme\getInstance(Queue::class);
        }

        throw new RuntimeException('Unable to instantiate queue, either provide a suitable container or inject the queue directly into the job class constructor as $queue');
    }
}
