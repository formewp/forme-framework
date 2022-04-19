<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use function Forme\getInstance;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigView implements ViewInterface
{
    use GetsDirectory;

    private \Twig\Environment $view;

    public function __construct()
    {
        $loader     = new FilesystemLoader($this->getDir() . '/../../views');
        $options    = [];
        if (WP_ENV==='production') {
            $options['cache'] =  FORME_PRIVATE_ROOT . 'view-cache';
        }

        $this->view = new Environment($loader, $options);
        $this->view->addExtension(getInstance(TwigExtension::class));
    }

    public function render(string $template, array $context = []): string
    {
        $template = str_replace('.', '/', $template);

        return $this->view->render($template . '.twig', $context);
    }
}
