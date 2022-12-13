<?php
declare(strict_types=1);

namespace Forme\Framework\Support;

use Forme\Framework\Controllers\Cypress\Create;
use Forme\Framework\Controllers\Cypress\CurrentUser;
use Forme\Framework\Controllers\Cypress\Login;
use Forme\Framework\Controllers\Cypress\Migrate;
use Forme\Framework\Controllers\Cypress\Rollback;
use Forme\Framework\Controllers\Cypress\RunPhp;
use Forme\Framework\Core\Router;
use Forme\Framework\Middleware\TestOnlyMiddleware;

final class EndToEndRoutes
{
    private const PREFIX = '/__cypress__';

    public function __invoke()
    {
        if (WP_ENV === 'testing') {
            Router::post(self::PREFIX . '/create', Create::class)->addMiddleware(TestOnlyMiddleware::class);
            Router::post(self::PREFIX . '/login', Login::class)->addMiddleware(TestOnlyMiddleware::class);
            Router::get(self::PREFIX . '/current-user', CurrentUser::class)->addMiddleware(TestOnlyMiddleware::class);
            Router::get(self::PREFIX . '/migrate', Migrate::class)->addMiddleware(TestOnlyMiddleware::class);
            Router::get(self::PREFIX . '/rollback', Rollback::class)->addMiddleware(TestOnlyMiddleware::class);
            Router::post(self::PREFIX . '/run-php', RunPhp::class)->addMiddleware(TestOnlyMiddleware::class);
        }
    }
}
