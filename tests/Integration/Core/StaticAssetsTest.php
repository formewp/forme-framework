<?php

use Forme\Framework\Core\Assets;

beforeEach(function () {
    $this->rootDir = dirname(__FILE__, 4) . '/wp-test/public/wp-content/themes/twentytwentyfour';
    // put a file in assets
    mkdir($this->rootDir . '/assets/static', 0777, true);
    file_put_contents($this->rootDir . '/assets/static/dummy.txt', 'dummy');
});

afterEach(function () {
    // remove the file in assets
    unlink($this->rootDir . '/assets/static/dummy.txt');
    rmdir($this->rootDir . '/assets/static');
});

test('time() returns the file time of the file', function () {
    expect(Assets::time('dummy.txt'))->toBe((string) filemtime($this->rootDir . '/assets/static/dummy.txt'));
});

test('path() returns the absolute path of the file', function () {
    expect(Assets::path('dummy.txt'))->toBe($this->rootDir . '/assets/static/dummy.txt');
});

test('uri() returns the uri of the file', function () {
    expect(Assets::uri('dummy.txt'))->toBe(get_template_directory_uri() . '/assets/static/dummy.txt');
});

test('distExists() returns false if no dist folder exists', function () {
    expect(Assets::distExists())->toBe(false);
});

test('staticExists() returns true if a static folder exists', function () {
    expect(Assets::staticExists())->toBe(true);
});
