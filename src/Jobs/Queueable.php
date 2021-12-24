<?php
declare(strict_types=1);

namespace Forme\Framework\Jobs;

trait Queueable
{
    public function dispatch(array $args = []): void
    {
        $queue = \Forme\getInstance('Forme\\Framework\\Jobs\\Queue');
        $queue->dispatch(['class' => get_class($this), 'arguments' => $args]);
    }

    public function schedule(array $args = []): void
    {
        $queue         = \Forme\getInstance('Forme\\Framework\\Jobs\\Queue');
        $args['class'] = get_class($this);
        $queue->schedule($args);
    }

    public function start(array $args = []): void
    {
        $queue         = \Forme\getInstance('Forme\\Framework\\Jobs\\Queue');
        $args['class'] = get_class($this);
        $queue->start($args);
    }

    public function stop(): void
    {
        $queue = \Forme\getInstance('Forme\\Framework\\Jobs\\Queue');
        $queue->stop(['class' => get_class($this)]);
    }
}
