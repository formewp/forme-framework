<?php

namespace Forme\Framework\View\Plates\Extension\Path;

use Forme\Framework\View\Plates;

final class PathExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates)
    {
        $c = $plates->getContainer();
        $c->add('path.resolvePath.prefixes', fn ($c) => (array) ($c->get('config')['base_dir'] ?? []));
        $c->addComposed('path.normalizeName', fn ($c) => [
            'path.stripExt'    => stripExtNormalizeName(),
            'path.stripPrefix' => stripPrefixNormalizeName($c->get('path.resolvePath.prefixes')),
        ]);
        $c->addStack('path.resolvePath', function ($c) {
            $config   = $c->get('config');
            $prefixes = $c->get('path.resolvePath.prefixes');

            return array_filter([
                'path.id'       => idResolvePath(),
                'path.prefix'   => $prefixes ? prefixResolvePath($prefixes, $c->get('fileExists')) : null,
                'path.ext'      => isset($config['ext']) ? extResolvePath($config['ext']) : null,
                'path.relative' => relativeResolvePath(),
            ]);
        });
        $plates->defineConfig([
            'ext'      => 'phtml',
            'base_dir' => null,
        ]);
        $plates->pushComposers(fn ($c) => [
            'path.normalizeName' => normalizeNameCompose($c->get('path.normalizeName')),
            'path.resolvePath'   => resolvePathCompose($c->get('path.resolvePath')),
        ]);
    }
}
