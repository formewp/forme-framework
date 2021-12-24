<?php
declare(strict_types=1);

namespace Forme\Framework\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Connection
{
    /** @var Capsule */
    private $capsule;

    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;
    }

    public function bootstrap(): void
    {
        // TODO bail if there is already a global instance
        global $wpdb;
        $args = [
            'driver'   => 'mysql',
            'host'     => DB_HOST,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset'  => DB_CHARSET,
            //'collation' => DB_COLLATE,
            'prefix'   => $wpdb->prefix ?: 'wp_',
        ];
        if (DB_COLLATE) {
            $args['collation'] = DB_COLLATE;
        }
        $this->capsule->addConnection($args);

        // Make this Capsule instance available globally via static methods
        $this->capsule->setAsGlobal();

        // Setup the Eloquent ORM
        $this->capsule->bootEloquent();
    }
}
