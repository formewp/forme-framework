<?php

use Forme\Framework\Core\Assets;

const THEME_DIR = '/tests/phpunit/data/themedir1/default';
const ASSETS_DIR = '/assets/dist';
const DUMMY_FILE = 'dummy.txt';

beforeEach(function () {
    $this->rootDir = dirname(__FILE__, 4) . '/wp'. THEME_DIR;
    // put a file and a manifest in assets/dist
    mkdir($this->rootDir . ASSETS_DIR, 0777, true);
    file_put_contents($this->rootDir . ASSETS_DIR . '/'.  DUMMY_FILE, 'dummy');
    file_put_contents($this->rootDir
        . '/assets/dist/manifest.json', '{"assets/dist/'.  DUMMY_FILE .'": "..'.THEME_DIR. ASSETS_DIR . '/'.  DUMMY_FILE.'"}');
});

afterEach(function () {
    // remove the files in assets
    unlink($this->rootDir . ASSETS_DIR . '/'.  DUMMY_FILE);
    unlink($this->rootDir . ASSETS_DIR . '/manifest.json');
    rmdir($this->rootDir . ASSETS_DIR);
    rmdir($this->rootDir . '/assets');
});

test('time() returns the file time of the file', function () {
    expect(Assets::time(DUMMY_FILE))->toBe((string) filemtime($this->rootDir . '/assets/dist/dummy.txt'));
});

test('path() returns the absolute path of the file', function () {
    expect(Assets::path(DUMMY_FILE))->toBe($this->rootDir . '/assets/dist/dummy.txt');
});

test('uri() returns the uri of the file', function () {
    expect(Assets::uri(DUMMY_FILE))->toBe('../tests/phpunit/data/themedir1/default/assets/dist/dummy.txt');
});

test('distExists() returns true if dist folder exists', function () {
    expect(Assets::distExists())->toBe(true);
});

test('staticExists() returns false if no static folder exists', function () {
    expect(Assets::staticExists())->toBe(false);
});
