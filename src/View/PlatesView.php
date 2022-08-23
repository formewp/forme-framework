<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use League\Plates\Engine;

class PlatesView implements ViewInterface
{
    use GetsDirectory;

    private \League\Plates\Engine $view;

    private const RELATIVE_VIEW_DIR = '/../../views';

    public function __construct()
    {
        $this->view = Engine::create($this->getDir() . self::RELATIVE_VIEW_DIR, 'plate.php');
    }

    public function render(string $template, array $context = []): string
    {
        return $this->view->render($template, $context);
    }
}
