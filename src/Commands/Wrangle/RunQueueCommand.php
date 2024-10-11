<?php

declare(strict_types=1);

namespace Forme\Framework\Commands\Wrangle;

use Forme\Framework\Jobs\Queue;
use function Forme\getInstance;
use function Laravel\Prompts\info;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunQueueCommand extends Command
{
    protected static $defaultName = 'queue:run';

    protected function configure(): void {
        $this->addArgument(name: 'name', mode: InputArgument::OPTIONAL, description: 'The queue name');
        $this->setDescription('Run the next job in the queue.');
        $this->setHelp("This command will run the next job in the queue. You can specify a queue name, otherwise it will run the default queue.");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $queueName = $input->getArgument('name') ?? null;
        
        $response = getInstance(Queue::class)->next($queueName);
        info($response);

        return Command::SUCCESS;
    }
}
