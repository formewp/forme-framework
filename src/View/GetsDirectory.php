<?php
declare(strict_types=1);

namespace Forme\Framework\View;

trait GetsDirectory
{
    protected function getDir(): string
    {
        $reflector = new \ReflectionClass($this::class);
        $filename  = $reflector->getFileName();

        return dirname($filename);
    }
}
