<?php

namespace Forme\Framework\View\Plates\Extension\Path;

use Forme\Framework\View\Plates\Template;

class ResolvePathArgs
{
    public function __construct(public $path, public array $context, public Template $template)
    {
    }

    public function withPath($path)
    {
        return new self($path, $this->context, $this->template);
    }

    public function withContext(array $context)
    {
        return new self($this->path, $context, $this->template);
    }

    public static function fromTemplate(Template $template)
    {
        return new self($template->name, [], clone $template);
    }
}
