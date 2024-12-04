<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

use ReflectionClass;

trait PluginOrThemeable
{
    /**
     * Checks whether a file is in a plugin folder.
     */
    protected static function isPlugin(): bool
    {
        return file_exists(static::getPluginPath());
    }

    /**
     * Get the file name of the child class.
     */
    protected static function getFileName(): string
    {
        $reflector = new ReflectionClass(static::class);

        return $reflector->getFileName() ?: '';
    }

    /**
     * Resolve plugin path
     * This is fairly brittle, we are assuming standard structure
     * And that the child class extending this is always in plugin-dir/app/Core.
     */
    protected static function getPluginPath(): string
    {
        $realClassDir = dirname(static::getFileName(), 3);
        $baseName     = basename($realClassDir);

        return realpath(WP_PLUGIN_DIR . '/' . $baseName) ?: '';
    }

    /**
     * Resolve theme path.
     */
    protected static function getThemePath(): string
    {
        return realpath(get_template_directory()) ?: '';
    }
}
