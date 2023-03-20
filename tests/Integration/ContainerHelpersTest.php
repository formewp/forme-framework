<?php

use DI\Container;
use Monolog\Logger;
use function Forme\getContainer;
use function Forme\getInstance;
use function Forme\log;
use function Forme\makeInstance;

it('returns a container instance', function () {
    expect(getContainer())->toBeInstanceOf(Container::class);
});

it('returns a logger instance', function () {
    expect(log())->toBeInstanceOf(Logger::class);
});

it('returns a singleton instance of a class', function () {
    $first      = getInstance(stdClass::class);
    $first->foo = faker()->text();
    $second     = getInstance(stdClass::class);
    expect($second->foo)->toBe($first->foo);
});

it('returns a new instance of a class', function () {
    $first       = makeInstance(stdClass::class);
    $first->foo  = faker()->text();
    $second      = getInstance(stdClass::class);
    $second->foo = faker()->text();
    $third       = makeInstance(stdClass::class);
    $third->foo  = faker()->text();
    expect($second->foo)->not()->toBe($first->foo);
    expect($third->foo)->not()->toBe($first->foo);
});
