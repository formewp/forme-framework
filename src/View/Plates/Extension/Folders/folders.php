<?php

namespace Forme\Framework\View\Plates\Extension\Folders;

use Closure;
use Forme\Framework\View\Plates;
use Forme\Framework\View\Plates\Extension\Path\ResolvePathArgs;

function foldersResolvePath(array $folders, string $sep = '::', string $file_exists = 'file_exists'): Closure
{
    return function (ResolvePathArgs $args, $next) use ($folders, $sep, $file_exists) {
        $path = null;
        if (!str_contains($args->path, $sep)) {
            return $next($args);
        }

        [$folder, $name] = explode($sep, $args->path);
        if (!isset($folders[$folder])) {
            return $next($args);
        }

        $folder_struct = $folders[$folder];

        foreach ($folder_struct['prefixes'] as $prefix) {
            $path = $next($args->withPath(
                Plates\Util\joinPath([$prefix, $name])
            ));

            // no need to check if file exists if we only have prefix
            if ((is_countable($folder_struct['prefixes']) ? count($folder_struct['prefixes']) : 0) == 1 || $file_exists($path)) {
                return $path;
            }
        }

        // none of the paths matched, just return what we have.
        return $path;
    };
}

function stripFoldersNormalizeName(array $folders, string $sep = '::'): Closure
{
    return function ($name) use ($folders, $sep) {
        foreach ($folders as $folder) {
            foreach (array_filter($folder['prefixes']) as $prefix) {
                if (str_starts_with($name, $prefix)) {
                    return $folder['folder'] . $sep . substr($name, strlen($prefix) + 1);
                }
            }
        }

        return $name;
    };
}
