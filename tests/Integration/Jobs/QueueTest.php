<?php

use Carbon\Carbon;
use Forme\Framework\Jobs\JobInterface;
use Forme\Framework\Jobs\Queue;
use Forme\Framework\Jobs\Queueable;
use Forme\Framework\Models\QueuedJob;
use function Forme\getContainer;

beforeEach(function () {
    $this->queue = getContainer()->get(Queue::class);
});

function fakeArguments(): array
{
    $arguments = array_map(fn () => faker()->word(), range(0, random_int(1, 5)));
    $keys      = array_map(fn () => faker()->word(), array_keys($arguments));

    return array_combine($keys, $arguments);
}

class TestJobClass implements JobInterface
{
    use Queueable;

    public function handle(array $args = []): ?string
    {
        return $args['response_string'];
    }
}

class FailingJobClass implements JobInterface
{
    use Queueable;

    public function handle(array $args = []): ?string
    {
        throw new Exception();
    }
}

it('dispatches an immediate job on the default queue', function () {
    $this->queue->dispatch(['class' => stdClass::class]);
    $job = QueuedJob::first();
    expect($job)->not()->toBeNull();
    expect($job->class)->toBe(stdClass::class);
    expect($job->queue_name)->toBeNull();
    expect($job->created_at->equalTo($job->scheduled_for))->toBeTrue();
});

it('dispatches an immediate job on a named queue with arguments', function () {
    $queueName = faker()->word();
    $arguments = fakeArguments();
    $this->queue->dispatch(['class' => stdClass::class, 'queue_name' => $queueName, 'arguments' => $arguments]);
    $job = QueuedJob::first();
    expect($job->queue_name)->toBe($queueName);
    expect(json_decode($job->arguments, true))->toBe($arguments);
});

it('schedules a job on a named queue with arguments', function () {
    $queueName = faker()->word();
    $arguments = fakeArguments();
    $this->queue->schedule(['class' => stdClass::class, 'queue_name' => $queueName, 'arguments' => $arguments, 'next' => 'tomorrow at 13:00']);
    $job = QueuedJob::first();
    expect($job->class)->toBe(stdClass::class);
    expect($job->queue_name)->toBe($queueName);
    expect(json_decode($job->arguments, true))->toBe($arguments);
    expect($job->scheduled_for->equalTo(Carbon::parse('tomorrow at 13:00')))->toBeTrue();
});

it('starts a recurring job for immediate dispatch on the default queue without arguments', function () {
    $this->queue->start(['class' => stdClass::class, 'frequency' => '1 week']);
    $job = QueuedJob::first();
    expect($job->class)->toBe(stdClass::class);
    expect($job->queue_name)->toBeNull();
    expect($job->created_at->equalTo($job->scheduled_for))->toBeTrue();
    expect($job->frequency)->toBe('1 week');
});

it('stops a recurring job on a named queue without arguments', function () {
    $queueName = faker()->word();
    $this->queue->start(['class' => stdClass::class, 'frequency' => '1 week', 'queue_name' => $queueName]);
    $this->queue->stop(['class' => stdClass::class, 'queue_name'=>$queueName]);
    $job = QueuedJob::first();
    expect($job)->toBeNull();
});

it('starts a recurring job for immediate dispatch on a named queue with arguments', function () {
    $queueName = faker()->word();
    $arguments = fakeArguments();
    $this->queue->start(['class' => stdClass::class, 'frequency' => '1 week', 'queue_name' => $queueName, 'arguments' => $arguments]);
    $job = QueuedJob::first();
    expect($job->class)->toBe(stdClass::class);
    expect($job->queue_name)->toBe($queueName);
    expect(json_decode($job->arguments, true))->toBe($arguments);
    expect($job->created_at->equalTo($job->scheduled_for))->toBeTrue();
    expect($job->frequency)->toBe('1 week');
});

it('stops a recurring job on a named queue with arguments', function () {
    $queueName = faker()->word();
    $arguments = fakeArguments();
    $this->queue->start(['class' => stdClass::class, 'frequency' => '1 week', 'queue_name' => $queueName, 'arguments' => $arguments]);
    $this->queue->stop(['class' => stdClass::class, 'queue_name'=>$queueName, 'arguments' => $arguments]);
    $job = QueuedJob::first();
    expect($job)->toBeNull();
});

it('stops a recurring job without arguments already started on the default queue from spawning a new one', function () {
    $this->queue->start(['class' => stdClass::class, 'frequency' => '1 week']);
    $job             = QueuedJob::first();
    $job->started_at = Carbon::now();
    $job->save();
    $this->queue->stop(['class' => stdClass::class]);
    $job->refresh();
    expect($job->frequency)->toBeNull();
});

it('stops a recurring job with arguments already started on a named queue from spawning a new one', function () {
    $queueName = faker()->word();
    $arguments = fakeArguments();
    $this->queue->start(['class' => stdClass::class, 'frequency' => '1 week', 'queue_name' => $queueName, 'arguments' => $arguments]);
    $job             = QueuedJob::first();
    $job->started_at = Carbon::now();
    $job->save();
    $this->queue->stop(['class' => stdClass::class, 'queue_name' => $queueName, 'arguments' => $arguments]);
    $job->refresh();
    expect($job->frequency)->toBeNull();
});

it('runs the next job in the default queue', function () {
    $responseString = faker()->words(6, true);
    $this->queue->dispatch(['class' => TestJobClass::class, 'arguments' => ['response_string' => $responseString]]);
    $response = $this->queue->next();
    expect($response)->toContain($responseString);
});

it('runs the next job in a named queue', function () {
    $responseString = faker()->words(6, true);
    $queueName      = faker()->word();
    $this->queue->dispatch(['class' => TestJobClass::class, 'arguments' => ['response_string' => $responseString], 'queue_name' => $queueName]);
    $response = $this->queue->next($queueName);
    expect($response)->toContain($responseString);
});

it('handles a job that throws an exception', function () {
    $this->queue->dispatch(['class' => FailingJobClass::class]);
    $response = $this->queue->next();
    expect($response)->toContain(' failed:');
});

it('stops recurring job if a job throws an exception', function () {
    $this->queue->start(['class' => FailingJobClass::class, 'frequency' => '1 minute']);
    $response = $this->queue->next();
    expect($response)->toContain(' failed:');
    $job = QueuedJob::where('class', FailingJobClass::class)->where('completed_at', null)->first();
    expect($job)->toBeNull();
});

    
