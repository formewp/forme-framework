<?php
declare(strict_types=1);

namespace Forme\Framework\Log;

use Exception;
use Forme\Framework\Models\Event;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

final class LogEventHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord|array $record): void
    {
        try {
            Event::create(['type' => 'log', 'payload' => $record]);
        } catch (Exception $e) {
            // noop - table might not exist, or mysql connection might be unavailable, but we don't really want to handle that here
        }
    }
}
