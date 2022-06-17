<?php

namespace Forme\Framework\View\Plates\Extension\LayoutSections;

use Forme\Framework\View\Plates;

final class LayoutRenderTemplate extends Plates\RenderTemplate\RenderTemplateDecorator
{
    public function renderTemplate(Plates\Template $template, ?Plates\RenderTemplate $rt = null) {
        $ref = $template->reference;
        $content = $this->render->renderTemplate($template, $rt ?: $this);

        $layout_ref = $ref()->get('layout');
        if (!$layout_ref) {
            return $content;
        }

        $layout = $layout_ref()->with('sections', $ref()->get('sections'));
        $layout->get('sections')->add('content', $content);

        return ($rt ?: $this)->renderTemplate($layout);
    }

    public static function factory() {
        return fn(Plates\RenderTemplate $render) => new self($render);
    }
}
