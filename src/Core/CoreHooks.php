<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use Forme\Framework\Http\Handlers\TemplateHandler;
use WP_Hook;

final class CoreHooks
{
    public function load(): void
    {
        // here we can define Must Run WP Hooks
        // for the moment it's just the template include along with a check to see if it already exists (for backwards compat)
        // if there are more in future, we should extract some of this logic out of here
        global $wp_filter;
        if (isset($wp_filter['template_include'])) {
            /** @var WP_Hook */
            $templateInclude = $wp_filter['template_include'];
            $alreadySet      = array_filter($templateInclude->callbacks, function ($filter) {
                $filter = array_values($filter);
                $filter = $filter[0];
                if (is_array($filter['function']) && isset($filter['function'][0])) {
                    return str_contains($filter['function'][0]::class, 'TemplateHandler');
                }
            });
            if ($alreadySet !== []) {
                return;
            }
        }

        // priority set to 11 to ensure that we run after third party plugins, specifically WooCommerce
        add_filter('template_include', \Forme\getInstance(TemplateHandler::class), 11);
    }
}
