<?php

declare(strict_types=1);

namespace Forme\Framework\Hooks;

use Forme\Framework\Commands\JobQueueCommand;
use WP_CLI;

final class JobCommandHook implements HookInterface
{
    public function maybeAdd(): void
    {
        if (HookIsSet::check('cli_init', JobQueueCommand::class)) {
            return;
        }

        add_action('cli_init', function () {
            WP_CLI::add_command('forme-queue', JobQueueCommand::class);
        });
    }
}
