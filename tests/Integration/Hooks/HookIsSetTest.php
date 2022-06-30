<?php

use Forme\Framework\Hooks\HookIsSet;
use Forme\Framework\Http\Handlers\TemplateHandler;

test('HookIsSet::check() returns true if a class hook is already set', function () {
    // set up a hook - todo - use a mock here
    add_filter('template_include', \Forme\getInstance(TemplateHandler::class), 11);
    $this->assertTrue(HookIsSet::check('template_include', TemplateHandler::class));
});
