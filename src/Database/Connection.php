<?php
declare(strict_types=1);

namespace Forme\Framework\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Connection
{
    public function __construct(private Capsule $capsule)
    {
    }

    public function bootstrap(): void
    {
        // TODO bail if there is already a global instance
        global $wpdb;
        if (WP_ENV === 'testing') {
            $args = [
                'driver'   => 'sqlite',
                'database' => DB_DIR . DB_FILE,
            ];
        } else {
            $args = [
                'driver'   => 'mysql',
                'host'     => DB_HOST,
                'database' => DB_NAME,
                'username' => DB_USER,
                'password' => DB_PASSWORD,
                'charset'  => DB_CHARSET,
            ];
            if (DB_COLLATE) {
                $args['collation'] = DB_COLLATE;
            }
        }
        $args['prefix'] = $wpdb->prefix ?: 'wp_';

        $this->capsule->addConnection($args);

        // Make this Capsule instance available globally via static methods
        $this->capsule->setAsGlobal();

        // Setup the Eloquent ORM
        $this->capsule->bootEloquent();
    }
}
