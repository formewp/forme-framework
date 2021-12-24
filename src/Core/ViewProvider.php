<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use Forme\Framework\View\Plates\Engine;

/**
 * @deprecated use Forme\Framework\View instead
 */
class ViewProvider
{
    /** @var Engine */
    protected $view;

    public function __construct()
    {
        $this->view = Engine::create($this->getDir() . '/../../views', 'plate.php');
    }

    public function get(): Engine
    {
        return $this->view;
    }

    private function getDir(): string
    {
        $reflector = new \ReflectionClass(get_class($this));
        $filename  = $reflector->getFileName();

        return dirname($filename);
    }
}
