<?php

// make sure wordpress is re-initialised before each test
uses()->beforeEach(function () {
    global $wp_filter;
    $wp_filter = [];
    migrate();
})->in('Integration');

uses()->afterEach(function () {
    rollback();
})->in('Integration');
