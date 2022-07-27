<?php

use Yoast\WPTestUtils\BrainMonkey\TestCase;

uses(TestCase::class)->in('WordPress');

// make sure wordpress is re-initialised before each test
uses()->beforeEach(function () {
    global $wp_filter;
    $wp_filter = [];
})->in('WordPress');
