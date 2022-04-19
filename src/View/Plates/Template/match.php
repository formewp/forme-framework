<?php

namespace Forme\Framework\View\Plates\Template;

use Forme\Framework\View\Plates\Template;

function matchPathExtension(callable $match)
{
    return matchAttribute('path', fn($path) => $match(pathinfo($path, PATHINFO_EXTENSION)));
}

function matchName($name)
{
    return fn(Template $template) => $template->get('normalized_name', $template->name) == $name;
}

function matchExtensions(array $extensions)
{
    return matchPathExtension(fn($ext) => in_array($ext, $extensions));
}

function matchAttribute($attribute, callable $match)
{
    return fn(Template $template) => $match($template->get($attribute));
}

function matchStub($res)
{
    return fn(Template $template) => $res;
}

function matchAny(array $matches)
{
    return function (Template $template) use ($matches) {
        foreach ($matches as $match) {
            if ($match($template)) {
                return true;
            }
        }

        return false;
    };
}

function matchAll(array $matches)
{
    return function (Template $template) use ($matches) {
        foreach ($matches as $match) {
            if (!$match($template)) {
                return false;
            }
        }

        return true;
    };
}
