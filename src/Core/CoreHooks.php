<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use Forme\Framework\Hooks\Template;

final class CoreHooks
{
    public function __construct(private Template $template)
    {
    }

    public function load(): void
    {
        // here we can add Must Run WP Hooks
        // if there are many more in future, we might want to refactor this logic
        $this->template->add();
    }
}
