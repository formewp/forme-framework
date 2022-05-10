<?php

namespace Forme\Framework\View\Plates\Extension\Data;

use Forme\Framework\View\Plates\Template;

function addGlobalsCompose(array $globals)
{
    return fn (Template $template) => $template->withData(array_merge($globals, $template->data));
}

function mergeParentDataCompose()
{
    return fn (Template $template) => $template->parent !== null
        ? $template->withData(array_merge($template->parent()->data, $template->data))
        : $template;
}

function perTemplateDataCompose(array $template_data_map)
{
    return function (Template $template) use ($template_data_map) {
        $name = $template->get('normalized_name', $template->name);

        return isset($template_data_map[$name])
            ? $template->withData(array_merge($template_data_map[$name], $template->data))
            : $template;
    };
}
