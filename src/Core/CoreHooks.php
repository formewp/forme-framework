<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use Forme\Framework\Hooks\EndToEndRoutesHook;
use Forme\Framework\Hooks\JobCommandHook;
use Forme\Framework\Hooks\TemplateHook;

final class CoreHooks
{
    public function __construct(private TemplateHook $template, private JobCommandHook $command, private EndToEndRoutesHook $endToEnd)
    {
    }

    public function load(): void
    {
        // here we can add Must Run WP Hooks
        // if there are many more in future, we might want to refactor this logic
        $this->template->maybeAdd();
        $this->command->maybeAdd();
        $this->endToEnd->maybeAdd();
    }
}
