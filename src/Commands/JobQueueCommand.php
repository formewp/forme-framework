<?php

declare(strict_types=1);

namespace Forme\Framework\Commands;

use Forme\Framework\Jobs\Queue;
use Psr\Container\ContainerInterface;
use WP_CLI;

class JobQueueCommand
{
    /** @var ContainerInterface */
    private $container;

    /** @var Queue */
    private $queue;

    public function __construct()
    {
        // since we are outside the usual forme loader flow we need to get the container
        $this->container      = \Forme\getContainer();
        $this->queue          = $this->container->get('Forme\\Framework\\Jobs\\Queue');
    }

    /**
     * Spec: It should
     * run the next job in the queue
     * return feedback response.
     */
    public function __invoke(): void
    {
        $response = $this->queue->next();
        WP_CLI::success($response);
    }
}
