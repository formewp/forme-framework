<?php
declare(strict_types=1);

namespace Forme\Framework\Hooks;

interface HookInterface
{
    public function maybeAdd(): void;
}
