<?php
declare(strict_types=1);

namespace Forme\Framework\View;

interface ViewInterface
{
    public function render(string $view, array $context = []): string;
}
