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
        if (preg_match('/\s*if\s*\(.*\)\s*\{.*\}/m', $contents)) {
            throw new RenderTemplateException('Single line if statements are not allowed in templates. Use a ternary or split up the statement ' . $template->get('path'));
        }
        // check if it contains "echo"
        if (strpos($contents, 'echo') !== false) {
            throw new RenderTemplateException('Use of "echo" is not allowed in templates. Use "<?=" instead. ' . $template->get('path'));
        }
        // check if any multiline php statements are present
        if (preg_match('/(?=.*<\?php)(?!.*\?>)/m', $contents)) {
            throw new RenderTemplateException('Multiline php statements are not allowed in templates. ' . $template->get('path'));
        }
        // check if any variable assignments are present - e.g. $foo = 'bar'
        if (preg_match('/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\s*=/', $contents)) {
            throw new RenderTemplateException('Variable assignments are not allowed in templates. Move this to a controller or extract into a helper function. ' . $template->get('path'));
        }
        if (preg_match('/(if|foreach|switch|for|while)\s*\(.*\).*\n*.*\{/m', $contents)) {
            throw new RenderTemplateException('Curly brackets are not allowed in template control structures. Please use alternative short syntax. ' . $template->get('path'));
        }
    }
}
