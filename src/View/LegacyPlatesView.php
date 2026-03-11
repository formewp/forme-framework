<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use Forme\Framework\View\Plates\Engine;

class LegacyPlatesView implements ViewInterface
{
    use GetsDirectory;

    protected Engine $view;

    protected const RELATIVE_VIEW_DIR = '/../../views';

    public function __construct()
    {
        $this->view = Engine::create($this->getDir() . self::RELATIVE_VIEW_DIR, 'plate.php');
    }

    public function render(string $template, array $context = []): string
    {
        return $this->view->render($template, $context);
    }
}
