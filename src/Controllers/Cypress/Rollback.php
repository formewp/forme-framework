<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers\Cypress;

use Exception;
use Forme\Framework\Controllers\AbstractController;
use Forme\Framework\Database\Migrations;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;

final class Rollback extends AbstractController
{
    public function __construct(private Migrations $migrations)
    {
    }

    public function __invoke(): ResponseInterface
    {
        // this is risky so let's make absolutely sure we are in test mode
        /** @var bool $testing */
        $testing = WP_ENV === 'testing';
        if (!$testing) {
            throw new Exception('You really don\'t want to run this outside of ephemeral test environments as it will wipe your database');
        }
        $this->migrations->rollback();

        return new EmptyResponse();
    }
}
