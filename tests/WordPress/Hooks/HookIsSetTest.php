<?php

use Forme\Framework\Hooks\HookIsSet;
use Forme\Framework\Http\Handlers\TemplateHandler;

test('returns true if a class hook is already set', function () {
    $mock = mock(TemplateHandler::class);
    add_filter('template_include', $mock, 11);
    expect(HookIsSet::check('template_include', get_class($mock)))->toBe(true);
});

test('returns false if a class hook is not already set', function () {
    expect(HookIsSet::check('template_include', TemplateHandler::class))->toBe(false);
});
