<?php

namespace Forme\Framework\View\Plates\RenderTemplate;

use Forme\Framework\View\Plates;

final class ValidatePathRenderTemplate extends RenderTemplateDecorator
{
    public function __construct(Plates\RenderTemplate $render, private $file_exists = 'file_exists') {
        parent::__construct($render);
    }

    public function renderTemplate(Plates\Template $template, ?Plates\RenderTemplate $rt = null) {
        $path = $template->get('path');
        if (!$path || ($this->file_exists)($path)) {
            return $this->render->renderTemplate($template, $rt ?: null);
        }

        throw new Plates\Exception\RenderTemplateException('Template path ' . $path . ' is not a valid path for template: ' . $template->name);
    }
}
