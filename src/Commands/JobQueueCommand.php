<?php

declare(strict_types=1);

namespace Forme\Framework\Commands;

use Forme\Framework\Jobs\Queue;
use function Forme\getContainer;
use Psr\Container\ContainerInterface;
use WP_CLI;

/**
 * @deprecated Use `wrangle queue:run` in Forme\Framework\Commands\Wrangle\RunQueueCommand instead
 **/
class JobQueueCommand
{
    private ContainerInterface $container;

    private Queue $queue;

    public function __construct()
    {
        // since we are outside the usual forme loader flow we need to get the container
        $this->container      = getContainer();
        $this->queue          = $this->container->get(Queue::class);
    }

    /**
     * Spec: It should
     * run the next job in the queue
     * return feedback response.
     */
    public function __invoke(array $args = []): void
    {
        $response = $this->queue->next($args[0] ?? null);
        WP_CLI::success($response);
    }
}
