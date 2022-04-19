<?php
declare(strict_types=1);

namespace Forme\Framework\Jobs;

trait Queueable
{
    public function dispatch(array $args = []): void
    {
        $queue = \Forme\getInstance(\Forme\Framework\Jobs\Queue::class);
        $queue->dispatch(['class' => $this::class, 'arguments' => $args]);
    }

    public function schedule(array $args = []): void
    {
        $queue         = \Forme\getInstance(\Forme\Framework\Jobs\Queue::class);
        $args['class'] = $this::class;
        $queue->schedule($args);
    }

    public function start(array $args = []): void
    {
        $queue         = \Forme\getInstance(\Forme\Framework\Jobs\Queue::class);
        $args['class'] = $this::class;
        $queue->start($args);
    }

    public function stop(): void
    {
        $queue = \Forme\getInstance(\Forme\Framework\Jobs\Queue::class);
        $queue->stop(['class' => $this::class]);
    }
}
