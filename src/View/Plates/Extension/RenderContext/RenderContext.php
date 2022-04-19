<?php

namespace Forme\Framework\View\Plates\Extension\RenderContext;

use BadMethodCallException;
use Forme\Framework\View\Plates;

final class RenderContext
{
    private $func_stack;

    public function __construct(
        private Plates\RenderTemplate $render,
        private Plates\TemplateReference $ref,
        $func_stack = null
    ) {
        $this->func_stack = $func_stack ?: Plates\Util\stack([platesFunc()]);
    }

    public function __get($name)
    {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot access ' . $name . ' because no func stack has been setup.');
        }

        return $this->invokeFuncStack($name, []);
    }

    public function __set($name, $value)
    {
        throw new BadMethodCallException('Cannot set ' . $name . ' on this render context.');
    }

    public function __call($name, array $args)
    {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot call ' . $name . ' because no func stack has been setup.');
        }

        return $this->invokeFuncStack($name, $args);
    }

    public function __invoke(array $args = [])
    {
        if (!$this->func_stack) {
            throw new BadMethodCallException('Cannot invoke the render context because no func stack has been setup.');
        }

        return $this->invokeFuncStack('__invoke', $args);
    }

    private function invokeFuncStack($name, array $args)
    {
        return ($this->func_stack)(new FuncArgs(
            $this->render,
            $this->ref,
            $name,
            $args
        ));
    }

    public static function factory(callable $create_render, $func_stack = null)
    {
        return fn (Plates\TemplateReference $ref) => new self(
            $create_render(),
            $ref,
            $func_stack
        );
    }
}
