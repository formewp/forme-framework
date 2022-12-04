<?php

// tests for the Forme\Framework\Core\Loader class

use Forme\Framework\Core\Loader;

test('adds an action to be registered with WordPress', function () {
    $loader = new Loader();
    $loader->addAction('action_name', stdClass::class, 'method_name');
    // get actions by reflection
    $actions = (new ReflectionClass($loader))->getProperty('actions');
    $actions->setAccessible(true);
    $actions = $actions->getValue($loader);
    expect($actions)->toHaveLength(1);
    expect($actions[0]['hook'])->toBe('action_name');
    expect($actions[0]['resolvedCallable'][0])->toBeInstanceOf(stdClass::class);
    expect($actions[0]['resolvedCallable'][1])->toBe('method_name');
    expect($actions[0]['priority'])->toBe(10);
    expect($actions[0]['numberOfArgs'])->toBe(1);
});

test('adds an invokable action to be registered with WordPress', function () {
    $loader = new Loader();
    // mock invokeable class
    $class = new class() {
        public function __invoke(): string
        {
            return 'foo bar';
        }
    };
    $loader->addAction('action_name', get_class($class), '__invoke');
    // get actions by reflection
    $actions = (new ReflectionClass($loader))->getProperty('actions');
    $actions->setAccessible(true);
    $actions = $actions->getValue($loader);
    expect($actions)->toHaveLength(1);
    expect($actions[0]['hook'])->toBe('action_name');
    expect($actions[0]['resolvedCallable'])->toBeInstanceOf(get_class($class));
    expect($actions[0]['priority'])->toBe(10);
    expect($actions[0]['numberOfArgs'])->toBe(1);
});

test('adds a filter to be registered with WordPress', function () {
    $loader = new Loader();
    $loader->addFilter('filter_name', stdClass::class, 'method_name');
    // get filters by reflection
    $filters = (new ReflectionClass($loader))->getProperty('filters');
    $filters->setAccessible(true);
    $filters = $filters->getValue($loader);
    expect($filters)->toHaveLength(1);
    expect($filters[0]['hook'])->toBe('filter_name');
    expect($filters[0]['resolvedCallable'][0])->toBeInstanceOf(stdClass::class);
    expect($filters[0]['resolvedCallable'][1])->toBe('method_name');
    expect($filters[0]['priority'])->toBe(10);
    expect($filters[0]['numberOfArgs'])->toBe(1);
});

test('adds an invokable filter to be registered with WordPress', function () {
    $loader = new Loader();
    // mock invokeable class
    $class = new class() {
        public function __invoke(): string
        {
            return 'foo bar';
        }
    };
    $loader->addFilter('filter_name', get_class($class), '__invoke');
    // get filters by reflection
    $filters = (new ReflectionClass($loader))->getProperty('filters');
    $filters->setAccessible(true);
    $filters = $filters->getValue($loader);
    expect($filters)->toHaveLength(1);
    expect($filters[0]['hook'])->toBe('filter_name');
    expect($filters[0]['resolvedCallable'])->toBeInstanceOf(get_class($class));
    expect($filters[0]['priority'])->toBe(10);
    expect($filters[0]['numberOfArgs'])->toBe(1);
});

test('adds a priority to a filter or action', function () {
    $loader = new Loader();
    $loader->addFilter('filter_name', stdClass::class, 'method_name', 20);
    $loader->addAction('action_name', stdClass::class, 'method_name', 20);
    // get filters by reflection
    $filters = (new ReflectionClass($loader))->getProperty('filters');
    $filters->setAccessible(true);
    $filters = $filters->getValue($loader);
    expect($filters[0]['priority'])->toBe(20);
    // get actions by reflection
    $actions = (new ReflectionClass($loader))->getProperty('actions');
    $actions->setAccessible(true);
    $actions = $actions->getValue($loader);
    expect($actions[0]['priority'])->toBe(20);
});

test('adds a number of arguments to a filter or action', function () {
    $loader = new Loader();
    $loader->addFilter('filter_name', stdClass::class, 'method_name', 10, 2);
    $loader->addAction('action_name', stdClass::class, 'method_name', 10, 2);
    // get filters by reflection
    $filters = (new ReflectionClass($loader))->getProperty('filters');
    $filters->setAccessible(true);
    $filters = $filters->getValue($loader);
    expect($filters[0]['numberOfArgs'])->toBe(2);
    // get actions by reflection
    $actions = (new ReflectionClass($loader))->getProperty('actions');
    $actions->setAccessible(true);
    $actions = $actions->getValue($loader);
    expect($actions[0]['numberOfArgs'])->toBe(2);
});

test('adds a yaml config file of hooks to be registered with WordPress', function () {
    $loader       = new Loader();
    $configString = file_get_contents(__DIR__ . '/hooks_test.yaml');
    $loader->addConfig($configString);
    // get actions by reflection
    $actions = (new ReflectionClass($loader))->getProperty('actions');
    $actions->setAccessible(true);
    $actions = $actions->getValue($loader);
    expect($actions)->toHaveLength(1);
    expect($actions[0]['hook'])->toBe('action_name');
    expect($actions[0]['resolvedCallable'][0])->toBeInstanceOf(stdClass::class);
    expect($actions[0]['resolvedCallable'][1])->toBe('method_name');
    expect($actions[0]['priority'])->toBe(10);
    expect($actions[0]['numberOfArgs'])->toBe(1);
    // get filters by reflection
    $filters = (new ReflectionClass($loader))->getProperty('filters');
    $filters->setAccessible(true);
    $filters = $filters->getValue($loader);
    expect($filters)->toHaveLength(1);
    expect($filters[0]['hook'])->toBe('filter_name');
    expect($filters[0]['resolvedCallable'][0])->toBeInstanceOf(stdClass::class);
    expect($filters[0]['resolvedCallable'][1])->toBe('method_name');
    expect($filters[0]['priority'])->toBe(1);
    expect($filters[0]['numberOfArgs'])->toBe(2);
});

test('run adds configured hooks to WordPress', function () {
    $loader       = new Loader();
    $configString = file_get_contents(__DIR__ . '/hooks_test.yaml');
    $loader->addConfig($configString);
    $loader->run();
    expect(has_action('action_name'))->toBe(true);
    expect(has_filter('filter_name'))->toBe(true);
});
