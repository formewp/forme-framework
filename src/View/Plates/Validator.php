<?php
declare(strict_types=1);

namespace Forme\Framework\View\Plates;

use Forme\Framework\View\Plates\Exception\RenderTemplateException;

final class Validator
{
    public static function validate(Template $template): void
    {
        // get the contents of the template
        $contents = file_get_contents($template->get('path'));
        // disallow single line if statements - e.g. if ($foo) {echo 'bar';};
        $errorLine = self::checkAndReturnLine('/\s*if\s*\(.*\)\s*\{.*\}/m', $contents);
        if ($errorLine) {
            throw new RenderTemplateException('Single line if statements are not allowed in templates. Use a ternary or split up the statement. Line ' . $errorLine . ' in ' . $template->get('path'));
        }
        // check if it contains "echo"
        $errorLine = self::checkAndReturnLine('/\s*echo\s*;/m', $contents);
        if ($errorLine) {
            throw new RenderTemplateException('Use of "echo" is not allowed in templates. Use "<?=" instead. Line ' . $errorLine . ' in ' . $template->get('path'));
        }
        // check if any multiline php statements are present
        $errorLine = self::checkAndReturnLine('/(?=.*<\?php)(?!.*\?>)/m', $contents);
        if ($errorLine) {
            throw new RenderTemplateException('Multiline php statements are not allowed in templates. Line ' . $errorLine . ' in ' . $template->get('path'));
        }
        // check if any variable assignments are present - e.g. $foo = 'bar'
        $errorLine = self::checkAndReturnLine('/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s*=/m', $contents);
        if ($errorLine) {
            throw new RenderTemplateException('Variable assignments are not allowed in templates. Move this to a controller or extract into a helper function. Line ' . $errorLine . ' in ' . $template->get('path'));
        }
        // check for control structures with curly brackets
        $errorLine = self::checkAndReturnLine('/(if|foreach|switch|for|while)\s*\(.*\).*\n*.*\{/m', $contents);
        if ($errorLine) {
            throw new RenderTemplateException('Curly brackets are not allowed in template control structures. Please use alternative short syntax. ' . $errorLine . ' in ' . $template->get('path'));
        }
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
