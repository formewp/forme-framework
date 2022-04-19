<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use Forme\Framework\View\Plates\Engine;

class LegacyPlatesView implements ViewInterface
{
    use GetsDirectory;

    private \Forme\Framework\View\Plates\Engine $view;

    public function __construct()
    {
        $this->view = Engine::create($this->getDir() . '/../../views', 'plate.php');
    }

    public function render(string $template, array $context = []): string
    {
        $template = str_replace('.', '/', $template);

        return $this->view->render($template, $context);
    }
}
