<?php

namespace Forme\Framework\View\Plates\Extension\LayoutSections;

use Forme\Framework\View\Plates;
use Forme\Framework\View\Plates\Extension\RenderContext\FuncArgs;
use function Forme\Framework\View\Plates\Extension\RenderContext\startBufferFunc;
use Forme\Framework\View\Plates\Template;

function sectionsCompose()
{
    return fn (Template $template) => $template->with('sections', $template->parent !== null ? $template->parent()->get('sections') : new Sections());
}

function layoutFunc()
{
    return function (FuncArgs $args) {
        [$name, $data] = $args->args;

        $layout = $args->template()->fork($name, $data ?: []);
        $args->template()->with('layout', $layout->reference);

        return $layout;
    };
}

function sectionFunc()
{
    return function (FuncArgs $args) {
        [$name, $else] = $args->args;

        $res = $args->template()->get('sections')->get($name);
        if ($res || !$else) {
            return $res;
        }

        return is_callable($else)
            ? Plates\Util\obWrap($else)
            : (string) $else;
    };
}

const START_APPEND  = 0;
const START_PREPEND = 1;
const START_REPLACE = 2;

/** Starts the output buffering for a section, update of 0 = replace, 1 = append, 2 = prepend */
function startFunc($update = START_REPLACE)
{
    return startBufferFunc(fn (FuncArgs $args) => function ($contents) use ($update, $args) {
        $name     = $args->args[0];
        $sections = $args->template()->get('sections');

        if ($update === START_APPEND) {
            $sections->append($name, $contents);
        } elseif ($update === START_PREPEND) {
            $sections->prepend($name, $contents);
        } else {
            $sections->add($name, $contents);
        }
    });
}
