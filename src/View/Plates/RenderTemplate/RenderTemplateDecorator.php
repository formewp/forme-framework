<?php

namespace Forme\Framework\View\Plates\RenderTemplate;

use Forme\Framework\View\Plates;

abstract class RenderTemplateDecorator implements Plates\RenderTemplate
{
    public function __construct(protected Plates\RenderTemplate $render)
    {
    }

    abstract public function renderTemplate(Plates\Template $template, ?Plates\RenderTemplate $rt = null);
}
