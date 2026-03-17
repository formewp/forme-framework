<?php

declare(strict_types=1);

namespace Forme\Framework\Core;

class Assets
{
    use PluginOrThemeable;

    public static function devServerActive(): bool
    {
        return file_exists(self::rootBasePath() . '/hot');
    }

    public static function viteClientUri(): string
    {
        return rtrim(self::devServerUrl(), '/') . '/@vite/client';
    }

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

    public static function uri(string $relativefilePath): string
    {
        if (self::devServerActive()) {
            return rtrim(self::devServerUrl(), '/') . '/' . self::findSourcePath($relativefilePath);
        }

        if (self::distExists()) {
            return self::resolveManifestPath($relativefilePath);
        }

        if (static::isPlugin()) {
            return plugin_dir_url(self::basePath() . 'dummy') . $relativefilePath;
        }

        return get_template_directory_uri() . self::basePathPart() . $relativefilePath;
    }

    public static function distExists(): bool
    {
        return is_dir(self::rootBasePath() . '/assets/dist');
    }

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

    protected static function basePath(): string
    {
        return self::rootBasePath() . self::basePathPart();
    }

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

    protected static function resolveManifestPath(string $filename): string
    {
        $file = self::resolveManifestFile($filename);

        if (!$file) {
            return $filename;
        }

        return self::buildDistUri($file);
    }

    private static function devServerUrl(): string
    {
        return trim(file_get_contents(self::rootBasePath() . '/hot'));
    }

    private static function manifestPath(): string
    {
        return self::basePath() . '.vite/manifest.json';
    }

    /**
     * Scan assets/src/ recursively to find the full source-relative path for a given filename.
     * Used in dev server mode where no manifest is available.
     * Falls back to the bare filename if not found.
     */
    private static function findSourcePath(string $filename): string
    {
        $srcDir = self::rootBasePath() . '/assets/src';

        if (!is_dir($srcDir)) {
            return $filename;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcDir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getFilename() === $filename) {
                return ltrim(str_replace(self::rootBasePath(), '', $file->getPathname()), '/');
            }
        }

        return $filename;
    }

    /**
     * Scan the Vite manifest for an entry whose key has a matching basename.
     * Returns the hashed output filename, or null if not found.
     */
    private static function resolveManifestFile(string $filename): ?string
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

        foreach ($manifestData as $key => $entry) {
            if (basename($key) === $filename) {
                return $entry['file'] ?? null;
            }
        }

        return null;
    }

    private static function buildDistUri(string $file): string
    {
        if (static::isPlugin()) {
            return plugin_dir_url(self::basePath() . 'dummy') . $file;
        }

        return get_template_directory_uri() . self::basePathPart() . $file;
    }
}
