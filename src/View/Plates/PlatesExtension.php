<?php

namespace Forme\Framework\View\Plates;

final class PlatesExtension implements Extension
{
    public function register(Engine $plates)
    {
        $c = $plates->getContainer();

        $c->add('config', [
            'validate_paths'   => true,
            'php_extensions'   => ['php', 'phtml'],
            'image_extensions' => ['png', 'jpg'],
        ]);
        $c->addComposed('compose', fn () => []);
        $c->add('fileExists', fn ($c) => 'file_exists');
        $c->add('renderTemplate', function ($c) {
            $rt = new RenderTemplate\FileSystemRenderTemplate([
                [
                    Template\matchExtensions($c->get('config')['php_extensions']),
                    new RenderTemplate\PhpRenderTemplate($c->get('renderTemplate.bind')),
                ],
                [
                    Template\matchExtensions($c->get('config')['image_extensions']),
                    RenderTemplate\MapContentRenderTemplate::base64Encode(new RenderTemplate\StaticFileRenderTemplate()),
                ],
                [
                    Template\matchStub(true),
                    new RenderTemplate\StaticFileRenderTemplate(),
                ],
            ]);
            if ($c->get('config')['validate_paths']) {
                $rt = new RenderTemplate\ValidatePathRenderTemplate($rt, $c->get('fileExists'));
            }

            $rt = array_reduce($c->get('renderTemplate.factories'), fn ($rt, $create) => $create($rt), $rt);
            $rt = new RenderTemplate\ComposeRenderTemplate($rt, $c->get('compose'));

            return $rt;
        });
        $c->add('renderTemplate.bind', fn () => Util\id());
        $c->add('renderTemplate.factories', fn () => []);

        $plates->addMethods([
            'pushComposers' => function (Engine $e, $def_composer) {
                $e->getContainer()->wrapComposed('compose', fn ($composed, $c) => array_merge($composed, $def_composer($c)));
            },
            'unshiftComposers' => function (Engine $e, $def_composer) {
                $e->getContainer()->wrapComposed('compose', fn ($composed, $c) => array_merge($def_composer($c), $composed));
            },
            'addConfig' => function (Engine $e, array $config) {
                $e->getContainer()->merge('config', $config);
            },
            /* merges in config values, but will defer to values already set in the config */
            'defineConfig' => function (Engine $e, array $config_def) {
                $config = $e->getContainer()->get('config');
                $e->getContainer()->add('config', array_merge($config_def, $config));
            },
        ]);
    }
}
