<?php

use Forme\Framework\Core\Assets;

const DIST_THEME_DIR   = '/public/wp-content/themes/twentytwentyfive';
const DIST_SOURCE_PATH = 'assets/src/js/app.js';
const DIST_HASHED_JS   = 'assets/app-CbcatIpB.js';
const DIST_HASHED_CSS  = 'assets/app-DiwrgTda.css';

beforeEach(function () {
    $this->rootDir = dirname(__FILE__, 4) . '/wp-test' . DIST_THEME_DIR;

    mkdir($this->rootDir . '/assets/dist/assets', 0777, true);
    mkdir($this->rootDir . '/assets/dist/.vite', 0777, true);

    file_put_contents($this->rootDir . '/assets/dist/' . DIST_HASHED_JS, 'js content');
    file_put_contents($this->rootDir . '/assets/dist/' . DIST_HASHED_CSS, 'css content');

    $manifest = json_encode([
        DIST_SOURCE_PATH => [
            'file'    => DIST_HASHED_JS,
            'css'     => [DIST_HASHED_CSS],
            'src'     => DIST_SOURCE_PATH,
            'isEntry' => true,
        ],
    ]);

    file_put_contents($this->rootDir . '/assets/dist/.vite/manifest.json', $manifest);
});

afterEach(function () {
    if (file_exists($this->rootDir . '/hot')) {
        unlink($this->rootDir . '/hot');
    }

    unlink($this->rootDir . '/assets/dist/' . DIST_HASHED_JS);
    unlink($this->rootDir . '/assets/dist/' . DIST_HASHED_CSS);
    unlink($this->rootDir . '/assets/dist/.vite/manifest.json');
    rmdir($this->rootDir . '/assets/dist/.vite');
    rmdir($this->rootDir . '/assets/dist/assets');
    rmdir($this->rootDir . '/assets/dist');
});

test('time() returns the manifest file time', function () {
    expect(Assets::time(DIST_SOURCE_PATH))
        ->toBe((string) filemtime($this->rootDir . '/assets/dist/.vite/manifest.json'));
});

test('path() returns the absolute path of the hashed file', function () {
    expect(Assets::path(DIST_SOURCE_PATH))
        ->toBe($this->rootDir . '/assets/dist/' . DIST_HASHED_JS);
});

test('uri() returns the uri of the hashed file', function () {
    expect(Assets::uri(DIST_SOURCE_PATH))
        ->toBe(get_template_directory_uri() . '/assets/dist/' . DIST_HASHED_JS);
});

test('cssFromManifest() returns an array of css uris', function () {
    expect(Assets::cssFromManifest(DIST_SOURCE_PATH))
        ->toBe([get_template_directory_uri() . '/assets/dist/' . DIST_HASHED_CSS]);
});

test('distExists() returns true if dist folder exists', function () {
    expect(Assets::distExists())->toBe(true);
});

test('staticExists() returns false if no static folder exists', function () {
    expect(Assets::staticExists())->toBe(false);
});

test('devServerActive() returns false when hot file does not exist', function () {
    expect(Assets::devServerActive())->toBe(false);
});

test('devServerActive() returns true when hot file exists', function () {
    file_put_contents($this->rootDir . '/hot', 'http://localhost:5173');
    expect(Assets::devServerActive())->toBe(true);
});

test('uri() returns dev server uri when hot file exists', function () {
    file_put_contents($this->rootDir . '/hot', 'http://localhost:5173');
    expect(Assets::uri(DIST_SOURCE_PATH))
        ->toBe('http://localhost:5173/' . DIST_SOURCE_PATH);
});

test('viteClientUri() returns the vite client uri when hot file exists', function () {
    file_put_contents($this->rootDir . '/hot', 'http://localhost:5173');
    expect(Assets::viteClientUri())->toBe('http://localhost:5173/@vite/client');
});

test('time() returns null when hot file exists', function () {
    file_put_contents($this->rootDir . '/hot', 'http://localhost:5173');
    expect(Assets::time(DIST_SOURCE_PATH))->toBeNull();
});
