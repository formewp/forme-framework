<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use Forme\Framework\View\Plates\Engine;

class LegacyPlatesView implements ViewInterface
{
    use GetsDirectory;

    private \Forme\Framework\View\Plates\Engine $view;

    private const RELATIVE_VIEW_DIR = '/../../views';

    public function __construct()
    {
        $this->view = Engine::create($this->getDir() . self::RELATIVE_VIEW_DIR, 'plate.php');
    }

    public function render(string $template, array $context = []): string
    {
        $template = str_replace('.', '/', $template);
        $viewDir  = $this->getDir() . self::RELATIVE_VIEW_DIR . '/';

        if (!file_exists($viewDir . $template . 'plate.php') && file_exists($viewDir . $template . '/index.plate.php')) {
            $template .= '/index';
        }

        return $this->view->render($template, $context);
    }
}
