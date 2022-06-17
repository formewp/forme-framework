<?php

namespace Forme\Framework\View\Plates\RenderTemplate;

use Forme\Framework\View\Plates;
use Throwable;
use Exception;

final class StaticFileRenderTemplate implements Plates\RenderTemplate
{
    public function __construct(private $get_contents = 'file_get_contents')
    {
    }

    public function renderTemplate(Plates\Template $template, ?Plates\RenderTemplate $rt = null) {
        $path = $template->get('path');
        return ($this->get_contents)($path);
    }
}
