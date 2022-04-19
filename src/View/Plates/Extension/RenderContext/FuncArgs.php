<?php

namespace Forme\Framework\View\Plates\Extension\RenderContext;

use Forme\Framework\View\Plates;

final class FuncArgs
{
    public function __construct(public Plates\RenderTemplate $render, public Plates\TemplateReference $ref, public $func_name, public $args = [])
    {
    }

    public function template()
    {
        return $this->ref->template;
    }

    public function withName($func_name)
    {
        return new self($this->render, $this->ref, $func_name, $this->args);
    }

    public function withArgs($args)
    {
        return new self($this->render, $this->ref, $this->func_name, $args);
    }
}
