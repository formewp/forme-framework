<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

class Assets
{
    use PluginOrThemeable;

    /**
     * send back the file time using path relative to assets directory
     * use this for versioning files e.g. Assets::time('style.css');.
     */
    public static function time(string $relativefilePath): ?string
    {
        $relativefilePath = self::resolveManifestPath($relativefilePath);

        return (string) filemtime(self::path($relativefilePath)) ?: null;
    }

    /**
     * send back the absolute path using path relative to stylesheet dir
     * e.g. Assets::path('style.css).
     */
    public static function path(string $relativefilePath): string
    {
        $relativefilePath = self::resolveManifestPath($relativefilePath);

        return self::basePath() . $relativefilePath;
    }

    /**
     * send back the uri based on a path relative to stylesheet dir
     * use this for asset links when enqueuing e.g. Assets::uri('style.css).
     */
    public static function uri(string $relativefilePath): string
    {
        // if there is a webpack dist then the manifest has our uri
        if (self::distExists()) {
            return self::resolveManifestPath($relativefilePath);
        }

        if (self::isPlugin()) {
            // we have to add a dummy because plugin_dir_ul assumes a file in a dir
            return plugin_dir_url(self::basePath() . 'dummy') . $relativefilePath;
        } else {
            return get_template_directory_uri() . self::basePathPart() . $relativefilePath;
        }
    }

    /**
     * Does a dist folder exist? i.e. is this a webpack or build tool situation.
     */
    public static function distExists(): bool
    {
        return is_dir(self::rootBasePath() . '/assets/dist');
    }

    /**
     * Does a static folder exist?
     */
    public static function staticExists(): bool
    {
        return is_dir(self::rootBasePath() . '/assets/static');
    }

    protected static function rootBasePath(): string
    {
        if (self::isPlugin()) {
            return self::getPluginPath();
        } else {
            return self::getThemePath();
        }
    }

    /**
     * Returns the assets base path.
     */
    protected static function basePath(): string
    {
        return self::rootBasePath() . self::basePathPart();
    }

    /**
     * Return the assets base path part, preferring dist then static if they exist.
     */
    protected static function basePathPart(): string
    {
        if (self::distExists()) {
            return '/assets/dist/';
        }

        if (self::staticExists()) {
            return '/assets/static/';
        }

        // for backwards compatibility
        return '/assets/';
    }

    /**
     * Return the resolved manifest path if one exists.
     */
    protected static function resolveManifestPath(string $path): string
    {
        if (!self::distExists()) {
            return $path;
        }

        $manifestFileLocation = self::basePath() . 'manifest.json';
        $manifestData         = json_decode(file_get_contents($manifestFileLocation), true, 512, JSON_THROW_ON_ERROR);

        $pathKey = ltrim(self::basePathPart(), '/') . $path;

        if (isset($manifestData[$pathKey])) {
            return $manifestData[$pathKey];
        }

        return $path;
    }
}
