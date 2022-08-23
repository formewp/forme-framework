<?php
declare(strict_types=1);

namespace Forme\Framework\View\Plates;

final class MagicResolver
{
    public static function resolve(string $name): string
    {
        $name     = str_replace('.', '/', $name);

        if (str_ends_with($name, '/')) {
            $name .= 'index';
        }

        return $name;
    }
}
