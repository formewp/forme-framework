<?php
declare(strict_types=1);

namespace Forme\Framework\View\Plates;

use Forme\Framework\View\Plates\Exception\RenderTemplateException;

final class Validator
{
    public const RULES = [
        [
            'pattern' => '/\s*if\s*\(.*\)\s*\{.*\}/m',
            'message' => 'Single line if statements are not allowed in Plates. Use a ternary or split up the statement.',
        ],
        [
            'pattern' => '/\s*echo\s*;/m',
            'message' => 'Echo statements are not allowed in Plates. Use "<?=" instead.',
        ],
        [
            'pattern' => '/(?=.*<\?php)(?!.*\?>)/m',
            'message' => 'Multiline php statements are not allowed in Plates. Split this up or extract the logic.',
        ],
        [
            'pattern' => '/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s*=/m',
            'message' => 'Variable assignments are not allowed in Plates. Move to a controller or extract into a helper function.',
        ],
        [
            'pattern' => '/(if|foreach|switch|for|while)\s*\(.*\).*\n*.*\{/m',
            'message' => 'Plates does not allow curly brackets in control structures. You must use short syntax.',
        ],
    ];

    public static function validate(Template $template): void
    {
        // get the contents of the template
        $path     = $template->get('path');
        $contents = file_get_contents($path);

        array_map(function (array $rule) use ($contents, $path) {
            $errorLine = self::checkAndReturnLine($rule['pattern'], $contents);
            if ($errorLine) {
                throw new RenderTemplateException($rule['message'] . ' Line ' . $errorLine . ' in ' . $path);
            }
        }, self::RULES);
    }

    private static function checkAndReturnLine(string $pattern, string $content): ?int
    {
        $match = preg_match(pattern: $pattern, subject: $content, matches: $matches, flags: PREG_OFFSET_CAPTURE);

        if ($match === false) {
            return null;
        }

        $characterPosition = (int) $matches[0][1];

        list($before) = str_split($content, $characterPosition);

        return strlen($before) - strlen(str_replace("\n", '', $before)) + 1;
    }
}
