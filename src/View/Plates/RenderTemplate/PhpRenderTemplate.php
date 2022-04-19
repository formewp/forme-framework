<?php

namespace Forme\Framework\View\Plates\RenderTemplate;

use Forme\Framework\View\Plates;

final class PhpRenderTemplate implements Plates\RenderTemplate
{
    private $bind;

    public function __construct(callable $bind = null) {
        $this->bind = $bind;
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $render = null) {
        $inc = self::createInclude();
        $inc = $this->bind ? ($this->bind)($inc, $template) : $inc;

        return Plates\Util\obWrap(function() use ($inc, $template) {
            $inc($template->get('path'), $template->data);
        });
    }

    private static function createInclude() {
        return function() {
            $funcGetArg = func_get_arg(1);
            extract($funcGetArg);
            include func_get_arg(0);
        };
    }
}
