<?php

use Forme\Framework\Jobs\Queue;
use Forme\Framework\Models\QueuedJob;
use function Forme\getContainer;

beforeEach(function () {
    $this->queue = getContainer()->get(Queue::class);
});

it('dispatches an immediate job on the default queue', function () {
    $this->queue->dispatch(['class' => stdClass::class]);
    $job = QueuedJob::first();
    expect($job)->not()->toBeNull();
    expect($job->class)->toBe(stdClass::class);
    expect($job->queue_name)->toBeNull();
    expect($job->created_at->equalTo($job->scheduled_for))->toBeTrue();
});

it('dispatches an immediate job on a named queue with arguments', function () {
    $this->queue->dispatch(['class' => stdClass::class, 'queue_name' => 'foo', 'arguments' => ['bar' => 'baz']]);
    $job = QueuedJob::first();
    expect($job->queue_name)->toBe('foo');
    expect($job->arguments)->toBe('{"bar":"baz"}');
});
