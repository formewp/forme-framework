<?php

namespace Forme\Framework\View\Blade;

use Log1x\SageDirectives\Directives as SageDirectives;

final class Directives
{
    /**
     * Directives.
     *
     * @var array
     */
    private $directives = [
        'ACF',
        'Helpers',
        'WordPress',
    ];

    /**
     * Returns the specified directives as an array.
     *
     * @param string $name
     *
     * @return ?array
     */
    private function get($name)
    {
        // we need to get log1x directives vendor directory via reflection
        $reflector = new \ReflectionClass(SageDirectives::class);
        $dir       = dirname($reflector->getFileName());

        if (file_exists($directiveSet = $dir . '/Directives/' . $name . '.php')) {
            return require_once $directiveSet;
        }

        return null;
    }

    /**
     * Returns a collection of directives.
     *
     * @return \Illuminate\Support\Collection
     */
    public function directives()
    {
        return collect($this->directives)
            ->flatMap(function ($directive) {
                if ($directive === 'ACF' && !function_exists('acf')) {
                    return null;
                }

                return $this->get($directive);
            });
    }
}
