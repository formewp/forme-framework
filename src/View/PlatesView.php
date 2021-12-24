<?php
declare(strict_types=1);

namespace Forme\Framework\View;

use League\Plates\Engine;

class PlatesView implements ViewInterface
{
    use GetsDirectory;

    /** @var Engine */
    private $view;

    public function __construct()
    {
        $this->view = new Engine($this->getDir() . '/../../views', 'plate.php');
    }

    public function render(string $template, array $context = []): string
    {
        $template = str_replace('.', '/', $template);

        return $this->view->render($template, $context);
    }
}
