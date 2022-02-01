<?php
declare(strict_types=1);

namespace Forme\Framework\View\Blade;

use Illuminate\Support\Collection;
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

    private function get(string $name): ?array
    {
        // we need to get log1x directives vendor directory via reflection
        $reflector = new \ReflectionClass(SageDirectives::class);
        $dir       = dirname($reflector->getFileName());

        if (file_exists($directiveSet = $dir . '/Directives/' . $name . '.php')) {
            return require_once $directiveSet;
        }

        return null;
    }

    public function directives(): Collection
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
