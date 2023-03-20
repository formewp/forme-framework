<?php

/**
 * This boilerplate file is auto-generated.
 */

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateEventsTable extends AbstractMigration
{
    public function change(): void
    {
        // create the table
        $table = $this->table('forme_events', ['signed' => false]);
        $table->addColumn('type', 'string')
            ->addColumn('payload', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
