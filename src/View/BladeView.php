<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use Forme\Framework\View\Blade\Directives;
use Jenssegers\Blade\Blade;

class BladeView implements ViewInterface
{
    use GetsDirectory;

    /** @var Blade */
    private $view;

    public function __construct(Directives $directives)
    {
        $this->view = new Blade($this->getDir() . '/../../views', FORME_PRIVATE_ROOT . 'view-cache');
        $directives->directives()
                ->each(function ($directive, $function) {
                    $this->view->directive($function, $directive);
                });
    }

    public function render(string $template, array $context = []): string
    {
        return $this->view->render($template, $context);
    }
}
