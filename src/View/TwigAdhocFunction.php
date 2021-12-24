<?php
declare(strict_types=1);

namespace Forme\Framework\View;

final class TwigAdhocFunction
{
    /**
     * @return mixed
     */
    public function exec(string $functionName)
    {
        $args = func_get_args();
        array_shift($args);
        if (is_string($functionName)) {
            $functionName = trim($functionName);
        }

        return call_user_func_array($functionName, ($args));
    }
}
