<?php

declare(strict_types=1);

namespace Forme\Framework\Jobs;

use Throwable;
use Exception;
use Forme\Framework\Models\QueuedJob;
use Illuminate\Support\Carbon;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class Queue
{
    public function __construct(private ContainerInterface $container, private LoggerInterface $logger) {}

    /**
     * Schedule a job
     * [
     *  'class' => 'Fully\\Qualified\\Class\\Name'
     *  'arguments' => ['foo' => 'bar']
     *  'next' => something that carbon can parse // the next time it should occur
     *  'frequency'  => if null/not set it's once, otherwise recurring so something that carbon can parse to add to the previous time
     *  'queue_name' => 'baz' // if not set it's the default queue
     * ].
     */
    public function schedule(array $args): void
    {
        // class, arguments, schedule
        $job                    = new QueuedJob();
        $job->class             = $args['class'];
        $job->arguments         = $this->encodeArguments($args['arguments'] ?? null);

        $job->scheduled_for     = Carbon::parse($args['next'] ?? 'now');
        $job->queue_name        = $args['queue_name'] ?? null;
        $job->frequency         = $args['frequency']  ?? null;
        $job->save();
        $this->logger->info('Scheduled job ' . $args['class'] . ' under id ' . $job->id . ' with a frequency of ' . ($args['frequency'] ?? 'unique'));
    }

    /**
     * Schedule a single job for immediate dispatch
     * [
     *  'class' => 'Fully\\Qualified\\Class\\Name'
     *  'arguments' => ['foo' => 'bar']
     *  'queue_name' => 'baz' // if not set it's the default queue
     * ].
     */
    public function dispatch(array $args): void
    {
        $args['next'] = 'now';
        $this->schedule($args);
    }

    /**
     * Schedule a unique recurring job. args must include frequency. uniqueness based on class name and arguments.
     */
    public function start(array $args): void
    {
        if (!isset($args['frequency'])) {
            throw new Exception('Job frequency missing');
        }

        $queueName = $args['queue_name'] ?? null;

        $jobExists = QueuedJob::where('class', $args['class'])
            ->where('queue_name', $queueName)
            ->where('arguments', $this->encodeArguments($args['arguments'] ?? null))
            ->whereNull('started_at')
            ->first();
        if (!$jobExists) {
            $this->logger->info('Starting recurring job ' . $args['class'] . ' with arguments ' . $this->encodeArguments($args['arguments'] ?? null));
            $this->schedule($args);
        } else {
            $this->logger->info("Didn't start recurring job " . $args['class'] . ' as it already exists with id ' . $jobExists->id);
        }
    }

    /**
     * Stop a unique recurring job by its class name and arguments.
     */
    public function stop(array $args): void
    {
        $queueName  = $args['queue_name'] ?? null;

        $pendingJob = QueuedJob::where('class', $args['class'])
            ->where('queue_name', $queueName)
            ->where('arguments', $this->encodeArguments($args['arguments'] ?? null))
            ->whereNull('started_at')
            ->first();
        if ($pendingJob) {
            // if there is a pending job we can just delete it
            $pendingJob->delete();
            $this->logger->info('Stopped recurring job ' . $args['class'] . ' with arguments ' . $this->encodeArguments($args['arguments'] ?? null));
        } else {
            // if there is a started job we set its frequency to null so it won't repeat
            $startedJob = QueuedJob::where('class', $args['class'])
                ->where('queue_name', $queueName)
                ->where('arguments', $this->encodeArguments($args['arguments'] ?? null))
                ->whereNull('completed_at')
                ->whereNotNull('frequency')
                ->first();
            if ($startedJob) {
                $startedJob->frequency = null;
                $startedJob->save();
                $this->logger->info('Stopped recurring job ' . $args['class'] . ' with arguments ' . $this->encodeArguments($args['arguments'] ?? null));
            } else {
                $this->logger->info("Didn't stop recurring job " . $args['class'] . ' as it does not exist');
            }
        }
    }

    public function next($queueName = null): string
    {
        $job = QueuedJob::whereNull('completed_at')
            ->whereNull('started_at')
            ->where('queue_name', $queueName)
            ->where('scheduled_for', '<=', Carbon::now())
            ->first();
        if (!$job) {
            $this->logger->info('No jobs in the queue');

            return 'No jobs in the queue';
        }

        $jobClass          = $this->container->get($job->class);
        $arguments         = json_decode($job->arguments, true, 512, JSON_THROW_ON_ERROR);
        $job->started_at   = Carbon::now();
        $job->save();
        $success = null;

        try {
            $response          = $jobClass->handle($arguments);
            $response = 'Ran job ' . $job->id . ': ' . $response;
            $this->logger->info('Ran job ' . $job->id . ': ' . $response);
            $success = true;

        } catch (Throwable $e) {
            $response = 'Job ' . $job->id . ' failed: ' . $e->getMessage();

            $this->logger->error($response, [
                'job_class' => $job->class,
                'job_arguments' => $arguments,
                'trace' => $e->getTraceAsString(),
            ]);

            $success = false;
        }

        if ($success) {
            $job->refresh();
            $job->completed_at = Carbon::now();
            $job->save();
        } else {
            $this->stop([
                'class'      => $job->class,
                'arguments'  => $arguments,
                'queue_name' => $queueName,
            ]);
        }

        // if this is a recurring job, queue up the next one
        // in future, we might have max retries in case of fails
        if ($job->frequency) {
            $nextTime = Carbon::create($job->scheduled_for)->add($job->frequency)->toDateTimeString();
            $this->schedule([
                'class'      => $job->class,
                'arguments'  => json_decode($job->arguments, true, 512, JSON_THROW_ON_ERROR),
                'next'       => $nextTime,
                'frequency'  => $job->frequency,
                'queue_name' => $queueName,
            ]);
        }

        return $response;
    }

    private function encodeArguments(array|null $arguments): string
    {
        $result = json_encode($arguments ?? null, JSON_THROW_ON_ERROR);

        $result = $result === 'null' ? '{}' : $result;

        return $result === '[]' ? '{}' : $result;
    }
}
