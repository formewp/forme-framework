<?php
declare(strict_types=1);

namespace Forme\Framework\Jobs;

use Exception;
use Forme\Framework\Models\QueuedJob;
use Illuminate\Support\Carbon;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class Queue
{
    public function __construct(private ContainerInterface $container, private LoggerInterface $logger)
    {
    }

    /**
     * Add a job to the queue
     * [
     *  'class' => 'Fully\\Qualified\\Class\\Name'
     *  'arguments' => ['foo' => 'bar']
     * ].
     */
    public function dispatch(array $args): void
    {
        // class, arguments
        $job                    = new QueuedJob();
        $job->class             =$args['class'];
        $job->arguments         = json_encode($args['arguments'] ?? null, JSON_THROW_ON_ERROR);
        $job->scheduled_for     = Carbon::now();
        $job->save();
        $this->logger->info('Added job ' . $job->id . ' to the queue');
    }

    /**
     * Schedule a job
     * [
     *  'class' => 'Fully\\Qualified\\Class\\Name'
     *  'arguments' => ['foo' => 'bar']
     *  'next' => something that carbon can parse // the next time it should occur
     *  'frequency'  => if null/not set it's once, otherwise recurring so something that carbon can parse to add to the previous time
     * ].
     */
    public function schedule(array $args): void
    {
        // class, arguments, schedule
        $job                    = new QueuedJob();
        $job->class             = $args['class'];
        $arguments              = json_encode($args['arguments'] ?? null, JSON_THROW_ON_ERROR);
        $job->arguments         = $arguments === 'null' ? '{}' : $arguments;

        $job->scheduled_for     = Carbon::parse($args['next']);
        $job->frequency         = $args['frequency'] ?? null;
        $job->save();
        $this->logger->info('Scheduled job ' . $args['class'] . ' under id ' . $job->id . ' with a frequency of ' . $args['frequency'] ?? 'unique');
    }

    /**
     * Schedule a unique recurring job. args must include frequency. uniqueness based on class name.
     */
    public function start(array $args): void
    {
        if (!isset($args['frequency'])) {
            throw new Exception('Job frequency missing');
        }

        $jobExists = QueuedJob::where('class', $args['class'])->whereNull('started_at')->first();
        if (!$jobExists) {
            $this->logger->info('Starting recurring job ' . $args['class']);
            $this->schedule($args);
        } else {
            $this->logger->info("Didn't start recurring job " . $args['class'] . ' as it already exists with id ' . $jobExists->id);
        }
    }

    /**
     * Stop a unique recurring job by its class name.
     */
    public function stop(array $args): void
    {
        $jobExists = QueuedJob::where('class', $args['class'])->whereNull('started_at')->first();
        if ($jobExists) {
            $jobExists->delete();
            $this->logger->info('Stopped recurring job ' . $args['class']);
        } else {
            $this->logger->info("Didn't stop recurring job " . $args['class'] . ' as it does not exist');
        }
    }

    public function next(): string
    {
        $job = QueuedJob::whereNull('completed_at')->whereNull('started_at')->where('scheduled_for', '<=', Carbon::now())->first();
        if (!$job) {
            $this->logger->info('No jobs in the queue');

            return 'No jobs in the queue';
        }

        $jobClass          = $this->container->get($job->class);
        $arguments         = json_decode($job->arguments, true, 512, JSON_THROW_ON_ERROR);
        $job->started_at   = Carbon::now();
        $job->save();
        $response          = $jobClass->handle($arguments);
        $job->completed_at = Carbon::now();
        $job->save();
        $this->logger->info('Ran job ' . $job->id . ': ' . $response);

        // if this is a recurring job, queue up the next one
        if ($job->frequency) {
            $nextTime = Carbon::create($job->scheduled_for)->add($job->frequency)->toDateTimeString();
            $this->schedule([
                'class'     => $job->class,
                'arguments' => json_decode($job->arguments, true, 512, JSON_THROW_ON_ERROR),
                'next'      => $nextTime,
                'frequency' => $job->frequency,
            ]);
        }

        return 'Ran job ' . $job->id . ': ' . $response;
    }
}
