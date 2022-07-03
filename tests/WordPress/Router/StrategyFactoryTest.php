<?php

use Forme\Framework\Router\Strategy\StrategyFactory;
use Forme\Framework\Router\Strategy\StrategyInterface;
use function Forme\getContainer;
use function Symfony\Component\String\u;

beforeEach(function () {
    $this->factory = getContainer()->get(StrategyFactory::class);
});

test('successfully creates all the configured route types', function () {
    $types     = $this->factory::TYPES;
    foreach ($types as $type) {
        $strategy = $this->factory->get($type);
        expect($strategy)->toBeInstanceOf(StrategyInterface::class);
    }
});

test('configured route types match the existing class files', function () {
    $strategyClassFiles = glob(__DIR__ . '/../../../src/Router/Strategy/*Strategy.php');
    $strategyClasses    = array_map(function ($file) {
        return basename($file, '.php');
    }, $strategyClassFiles);
    $configuredTypes           = $this->factory::TYPES;
    $configuredTypeClasses     = array_map(function ($type) {
        return u($type)->camel()->title()->toString() . 'Strategy';
    }, $configuredTypes);
    // check that each array contains the same values
    expect(array_diff($strategyClasses, $configuredTypeClasses))->toBeEmpty();
});
