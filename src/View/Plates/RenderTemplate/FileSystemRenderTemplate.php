<?php

namespace Forme\Framework\View\Plates\RenderTemplate;

use Forme\Framework\View\Plates;

final class FileSystemRenderTemplate implements Plates\RenderTemplate
{
    public function __construct(private array $render_sets)
    {
    }

    public function renderTemplate(Plates\Template $template, ?Plates\RenderTemplate $rt = null) {
        foreach ($this->render_sets as [$match, $render]) {
            if ($match($template)) {
                return $render->renderTemplate($template, $rt ?: $this);
            }
        }

        throw new Plates\Exception\RenderTemplateException('No renderer was available for the template: ' . $template->name);
    }
}
