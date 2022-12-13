<?php

declare(strict_types=1);

namespace Forme\Framework\Hooks;

use Forme\Framework\Support\EndToEndRoutes;
use function Forme\getInstance;

final class EndToEndRoutesHook implements HookInterface
{
    public function maybeAdd(): void
    {
        if (HookIsSet::check('init', EndToEndRoutes::class)) {
            return;
        }

        add_action('init', getInstance(EndToEndRoutes::class));
    }
}
