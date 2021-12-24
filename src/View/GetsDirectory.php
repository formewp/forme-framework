<?php
declare(strict_types=1);

namespace Forme\Framework\View;

trait GetsDirectory
{
    private function getDir(): string
    {
        $reflector = new \ReflectionClass(get_class($this));
        $filename  = $reflector->getFileName();

        return dirname($filename);
    }
}
