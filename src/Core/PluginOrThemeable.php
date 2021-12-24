<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

trait PluginOrThemeable
{
    /**
     * Checks whether a file is in a plugin folder.
     */
    protected static function isPlugin()
    {
        return file_exists(self::getPluginPath());
    }

    /**
     * Get the file name of the child class.
     */
    protected static function getFileName()
    {
        $reflector = new \ReflectionClass(get_called_class());

        return $reflector->getFileName();
    }

    /**
     * Resolve plugin path
     * This is fairly brittle, we are assuming standard structure
     * And that the child class extending this is always in plugin-dir/app/Core.
     */
    protected static function getPluginPath()
    {
        $realClassDir = dirname(self::getFileName(), 3);
        $baseName     = basename($realClassDir);

        return WP_PLUGIN_DIR . '/' . $baseName;
    }

    /**
     * Resolve theme path.
     */
    protected static function getThemePath()
    {
        return get_template_directory();
    }
}
