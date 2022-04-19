<?php

namespace Forme\Framework\View\Plates\RenderTemplate;

use Forme\Framework\View\Plates;

final class MockRenderTemplate implements Plates\RenderTemplate
{
    public function __construct(private array $mocks)
    {
    }

    public function renderTemplate(Plates\Template $template, Plates\RenderTemplate $rt = null) {
        if (!isset($this->mocks[$template->name])) {
            throw new Plates\Exception\RenderTemplateException('Mock include does not exist for name: ' . $template->name);
        }

        return $this->mocks[$template->name]($template);
    }
}
