<?php
declare(strict_types=1);

namespace Forme\Framework\View\Blade;

use Illuminate\Support\Collection;
use Log1x\SageDirectives\Directives as SageDirectives;

final class Directives
{
    /**
     * @var array
     */
    private $directives = [
        'ACF',
        'Helpers',
        'WordPress',
        'Local',
    ];

    private function get(string $name): ?array
    {
        $reflector = new \ReflectionClass(SageDirectives::class);
        $dir       = dirname($reflector->getFileName());

        if (file_exists($directiveSet = $dir . '/Directives/' . $name . '.php')) {
            return require_once $directiveSet;
        }

        return null;
    }

    public function collect(): Collection
    {
        return collect($this->directives)
            ->flatMap(function ($directive) {
                if ($directive === 'ACF' && !function_exists('acf')) {
                    return null;
                }

                if ($directive === 'Local') {
                    return require_once __DIR__ . '/Local.php';
                }

                return $this->get($directive);
            });
    }
}
