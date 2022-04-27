<?php
declare(strict_types=1);

namespace Forme\Framework\Core;

/**
 * @deprecated use Forme\Framework\View instead
 */
class View
{
    use PluginOrThemeable;

    /** @var string */
    private $basePath;

    public function __construct(string $basePath = '')
    {
        // if this is a plugin and no basepath is passed in, let's populate it automatically
        if (!$basePath && self::isPlugin()) {
            $this->basePath = self::getPluginPath();
        } else {
            $this->basePath = $basePath;
        }
    }

    /**
     * @return void|string|false
     */
    public function render(string $file, array $fields = [], bool $returnString = false)
    {
        /**
         * deprecated, this is here for backward compatibility.
         */
        $templateArgs = $fields;

        // we neeed to end with the simplest, otherwise we might get root files when we probably don't want them
        if (file_exists($this->basePath . '/views/' . $file . '.plate.php')) {
            $filePath = $this->basePath . '/views/' . $file . '.plate.php';
        } elseif (file_exists(get_stylesheet_directory() . '/views/' . $file . '.plate.php')) {
            $filePath = get_stylesheet_directory() . '/views/' . $file . '.plate.php';
        } elseif (file_exists(get_template_directory() . '/views/' . $file . '.plate.php')) {
            $filePath = get_template_directory() . '/views/' . $file . '.plate.php';
        } elseif (file_exists(get_stylesheet_directory() . '/' . $file . '.plate.php')) {
            $filePath = get_stylesheet_directory() . '/' . $file . '.plate.php';
        } elseif (file_exists(get_template_directory() . '/' . $file . '.plate.php')) {
            $filePath = get_template_directory() . '/' . $file . '.plate.php';
        } elseif (file_exists($this->basePath . '/' . $file . '.plate.php')) {
            $filePath = $this->basePath . '/' . $file . '.plate.php';
        } else {
            $filePath = $file;
        }

        // extract the fields from the array out into the function scope
        // collisions are ignored, array still exists
        extract($fields, EXTR_SKIP);

        $v = $this;

        // render the template into a string
        ob_start();
        $requiredFile   = require $filePath;
        $templateString = ob_get_clean();

        // if return is set, return the string instead of outputting it
        if ($returnString) {
            if ($requiredFile === false) {
                return false;
            } else {
                return $templateString;
            }
        }

        // output the string
        echo $templateString;
    }
}
