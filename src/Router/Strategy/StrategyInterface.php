<?php
declare(strict_types=1);

namespace Forme\Framework\Router\Strategy;

use Forme\Framework\Http\Handlers\HandlerInterface;

interface StrategyInterface
{
    public function convert(string $route, callable $handler, ?string $method): HandlerInterface;
}
