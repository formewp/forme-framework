<?php
declare(strict_types=1);

namespace Forme\Framework\Jobs;

interface JobInterface
{
    public function handle(array $args = []): ?string;

    public function dispatch(array $args = []): void;

    public function schedule(array $args = []): void;
}
