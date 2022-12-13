<?php
declare(strict_types=1);

namespace Forme\Framework\Controllers\Cypress;

use Forme\Framework\Controllers\AbstractController;
use Forme\Framework\Database\Migrations;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;

final class Migrate extends AbstractController
{
    public function __construct(private Migrations $migrations)
    {
    }

    public function __invoke(): ResponseInterface
    {
        $this->migrations->migrate();

        return new EmptyResponse();
    }
}