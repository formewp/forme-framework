<?php

namespace Forme\Framework\View\Plates\Extension\LayoutSections;

use Forme\Framework\View\Plates;

final class DefaultLayoutRenderTemplate extends Plates\RenderTemplate\RenderTemplateDecorator
{
    public function __construct(Plates\RenderTemplate $render, private $layout_path) {
        parent::__construct($render);
    }

    public function renderTemplate(Plates\Template $template, ?Plates\RenderTemplate $rt = null) {
        if ($template->parent || $template->get('no_layout')) {
            return $this->render->renderTemplate($template, $rt ?: $this);
        }

        $ref = $template->reference;
        $contents = $this->render->renderTemplate($template, $rt ?: $this);

        if ($ref()->get('layout')) {
            return $contents;
        }

        $layout = $ref()->fork($this->layout_path);
        $ref()->with('layout', $layout->reference);

        return $contents;
    }

    public static function factory($layout_path) {
        return fn(Plates\RenderTemplate $rt) => new self($rt, $layout_path);
    }
}
