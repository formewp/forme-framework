<?php

declare(strict_types=1);

namespace Forme\Framework\Core;

class Assets
{
    use PluginOrThemeable;

    /**
     * Check whether the Vite dev server is active by presence of hot file.
     */
    public static function devServerActive(): bool
    {
        return file_exists(self::rootBasePath() . '/hot');
    }

    /**
     * Return the URI for the Vite client script.
     */
    public static function viteClientUri(): string
    {
        return rtrim(self::devServerUrl(), '/') . '/@vite/client';
    }

    /**
     * Return an array of CSS URIs for a given manifest entry source path.
     * Returns an empty array when not in dist mode or when no CSS is associated.
     *
     * @return string[]
     */
    public static function cssFromManifest(string $path): array
    {
        if (!self::distExists() || !file_exists(self::manifestPath())) {
            return [];
        }

        $manifestData = json_decode(
            file_get_contents(self::manifestPath()),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (empty($manifestData[$path]['css'])) {
            return [];
        }

        return array_map(
            static fn(string $cssFile): string => self::buildDistUri($cssFile),
            $manifestData[$path]['css']
        );
    }

    /**
     * Return the file time using path relative to the source assets directory.
     * In dev server mode returns null. In dist mode returns the manifest mtime.
     * Use this for versioning e.g. Assets::time('assets/src/js/app.js').
     */
    public static function time(string $relativefilePath): ?string
    {
        if (self::devServerActive()) {
            return null;
        }

        if (self::distExists()) {
            if (!file_exists(self::manifestPath())) {
                return null;
            }

            return (string) filemtime(self::manifestPath());
        }

        if (!self::path($relativefilePath)) {
            return null;
        }

        return (string) filemtime(self::path($relativefilePath)) ?: null;
    }

    /**
     * Return the absolute path for a given source file path.
     * In dist mode, resolves via the Vite manifest to the hashed file.
     * e.g. Assets::path('assets/src/js/app.js').
     */
    public static function path(string $relativefilePath): ?string
    {
        if (self::distExists()) {
            $file = self::resolveManifestFile($relativefilePath);

            if (!$file) {
                return null;
            }

            $path = realpath(self::basePath() . $file);

            return $path && file_exists($path) ? $path : null;
        }

        return realpath(self::basePath() . $relativefilePath) ?: null;
    }

    /**
     * Return the URI based on a source-relative path.
     * In dev server mode returns a dev server URL.
     * In dist mode resolves via the Vite manifest to the hashed file URI.
     * e.g. Assets::uri('assets/src/js/app.js').
     */
    public static function uri(string $relativefilePath): string
    {
        if (self::devServerActive()) {
            return rtrim(self::devServerUrl(), '/') . '/' . ltrim($relativefilePath, '/');
        }

        if (self::distExists()) {
            return self::resolveManifestPath($relativefilePath);
        }

        if (static::isPlugin()) {
            return plugin_dir_url(self::basePath() . 'dummy') . $relativefilePath;
        }

        return get_template_directory_uri() . self::basePathPart() . $relativefilePath;
    }

    /**
     * Does a dist folder exist? i.e. has a Vite build been run.
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
        if (static::isPlugin()) {
            return self::getPluginPath();
        }

        return self::getThemePath();
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

        return '/assets/';
    }

    /**
     * Return the resolved manifest URI for a given source path.
     */
    protected static function resolveManifestPath(string $path): string
    {
        $file = self::resolveManifestFile($path);

        if (!$file) {
            return $path;
        }

        return self::buildDistUri($file);
    }

    /**
     * Read the dev server URL from the hot file.
     */
    private static function devServerUrl(): string
    {
        return trim(file_get_contents(self::rootBasePath() . '/hot'));
    }

    /**
     * Return the absolute filesystem path to the Vite manifest.
     */
    private static function manifestPath(): string
    {
        return self::basePath() . '.vite/manifest.json';
    }

    /**
     * Look up the hashed filename for a source path in the Vite manifest.
     * Returns null if the manifest or entry cannot be found.
     */
    private static function resolveManifestFile(string $path): ?string
    {
        if (!self::distExists() || !file_exists(self::manifestPath())) {
            return null;
        }

        $manifestData = json_decode(
            file_get_contents(self::manifestPath()),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return $manifestData[$path]['file'] ?? null;
    }

    /**
     * Build a full public URI for a file path relative to the dist directory.
     */
    private static function buildDistUri(string $file): string
    {
        if (static::isPlugin()) {
            return plugin_dir_url(self::basePath() . 'dummy') . $file;
        }

        return get_template_directory_uri() . self::basePathPart() . $file;
    }
}
