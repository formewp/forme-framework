<?php
declare(strict_types=1);

namespace Forme\Framework\Hooks;

use Forme\Framework\Http\Handlers\TemplateHandler;
use WP_Hook;

final class TemplateHook implements HookInterface
{
    public function maybeAdd(): void
    {
        //  template include along with a check to see if it already exists (for backwards compat)
        if (HookIsSet::check('template_include', TemplateHandler::class)) {
            return;
        }

        // priority set to 12 to ensure that we run after third party plugins, specifically WooCommerce (10) and Elementor (11)
        add_filter('template_include', \Forme\getInstance(TemplateHandler::class), 12);
    }
}
