<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use Jenssegers\Blade\Blade;

class BladeView implements ViewInterface
{
    use GetsDirectory;

    /** @var Blade */
    private $view;

    public function __construct()
    {
        $this->view = new Blade($this->getDir() . '/../../views', FORME_PRIVATE_ROOT . 'view-cache');
    }

    public function render(string $template, array $context = []): string
    {
        return $this->view->render($template, $context);
    }
}
