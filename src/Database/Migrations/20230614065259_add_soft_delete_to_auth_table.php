<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddSoftDeleteToAuthTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('forme_auth_tokens');
        $table->addColumn('deleted_at', 'datetime', ['null' => true]);
        $table->save();
    }
}
