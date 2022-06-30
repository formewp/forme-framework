<?php
declare(strict_types=1);

namespace Forme\Framework\Hooks;

use WP_Hook;

final class HookIsSet
{
    public static function check(string $hook, string $callable): bool
    {
        global $wp_filter;
        if (isset($wp_filter[$hook])) {
            /** @var WP_Hook */
            $templateInclude = $wp_filter[$hook];
            $alreadySet      = array_filter($templateInclude->callbacks, function ($filter) use ($callable) {
                $filter = array_values($filter);
                $filter = $filter[0];
                if (is_array($filter['function']) && isset($filter['function'][0])) {
                    return str_contains($filter['function'][0]::class, $callable);
                } elseif (is_string($filter['function'])) {
                    return str_contains($filter['function'], $callable);
                } elseif ($filter['function'] instanceof $callable || $filter['function'] == $callable) {
                    return true;
                }
            });
            if ($alreadySet !== []) {
                return true;
            }
        }

        return false;
    }
}
