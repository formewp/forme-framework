<?php
declare(strict_types=1);

namespace Forme\Framework\Log;

use Closure;
use Spatie\Enum\Enum;

/**
 * @method static self EVENT()
 * @method static self FILE()
 */
final class LogHandlerType extends Enum
{
    protected static function values(): Closure
    {
        return function (string $name): string {
            return mb_strtolower($name);
        };
    }
}
