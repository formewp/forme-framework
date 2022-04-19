<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->bootstrapFiles([
        __DIR__ . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
    ]);

    // define sets of rules
    $rectorConfig->sets([
       LevelSetList::UP_TO_PHP_80,
       SetList::CODE_QUALITY,
       SetList::CODING_STYLE,
       SetList::DEAD_CODE,
    ]);

    $rectorConfig->skip([
        // skip deprecated classes
        __DIR__ . '/src/Core/TemplateHandler.php',
        __DIR__ . '/src/Core/BootstrapNavWalker.php',
        __DIR__ . '/src/Core/LegacyBootstrapNavWalker.php',
        __DIR__ . '/src/Core/ViewProvider.php',
        __DIR__ . '/src/Core/View.php',
        // skip var annotation rule since it flags where we use it to coerce variable types
        RemoveNonExistingVarAnnotationRector::class,
    ]);
};
