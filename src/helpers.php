<?php

declare(strict_types=1);

namespace Forme;

use Forme\Framework\Router\AltoRouter;
use DI\ContainerBuilder;
use DI\FactoryInterface;
use Forme\Framework\Http\ServerRequest;
use Forme\Framework\Log\LogEventHandler;
use Forme\Framework\Log\LogHandlerType;
use InvalidArgumentException;
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\UnicodeString;
use function DI\factory;

// Bootstrap functions

if (!function_exists(__NAMESPACE__ . '\bootstrap')) {
    /**
     * Bootstrap all the things.
     */
    function bootstrap(): void
    {
        bootstrapDotenv();
        bootstrapWhoops();
        bootstrapContainer();
    }
}

if (!function_exists(__NAMESPACE__ . '\bootstrapContainer')) {
    /**
     * Bootstrap the container and set definitions.
     */
    function bootstrapContainer(): ContainerInterface|FactoryInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            LoggerInterface::class => factory(function () {
                $logger            = new Logger('forme');
                $logHandlerTypeVal = WP_ENV === 'production' ? 'event' : 'file';
                // config over-ride
                $logHandlerTypeVal = env('LOG_HANDLER') ?: $logHandlerTypeVal;
                // this next line will throw an exception if not valid
                $logHandlerType = LogHandlerType::from($logHandlerTypeVal);
                if ($logHandlerType->equals(LogHandlerType::FILE())) {
                    $logFile     = FORME_PRIVATE_ROOT . '/logs/forme.log';
                    $fileHandler = new RotatingFileHandler($logFile);
                    $logger->pushHandler($fileHandler);
                } else {
                    $logEventHandler = new LogEventHandler();
                    $logger->pushHandler($logEventHandler);
                }

                return $logger;
            }),
            AltoRouter::class => factory(function () {
                $siteUrl      = get_option('home') ?: get_option('siteurl');
                $siteUrlParts = explode('/', $siteUrl);
                $siteUrlParts = array_slice($siteUrlParts, 3);

                $basePath     = implode('/', $siteUrlParts);
                if ($basePath === '' || $basePath === '0') {
                    $basePath = '/';
                } else {
                    $basePath = '/' . $basePath . '/';
                }

                // Clean any double slashes that have resulted
                $basePath = str_replace('//', '/', $basePath);
                $router   = new AltoRouter();
                $router->setBasePath($basePath);

                return $router;
            }),
        ]);
        $container  = $builder->build();
        $connection = $container->get(\Forme\Framework\Database\Connection::class);
        $connection->bootstrap();
        $GLOBALS['__forme_container__'] = $container;

        return $container;
    }
}

if (!function_exists(__NAMESPACE__ . '\bootstrapWhoops')) {
    /**
     * Load Whoops if dev and not already loaded.
     *
     **/
    function bootstrapWhoops(): void
    {
        if (WP_ENV == 'development' && !isset($GLOBALS['__forme_whoops__'])) {
            $whoops  = new \Whoops\Run();
            $handler = new \Whoops\Handler\PrettyPageHandler();
            if (env('WHOOPS_EDITOR')) {
                $handler->setEditor(env('WHOOPS_EDITOR'));
            }

            $whoops->pushHandler($handler);
            $whoops->register();
            set_error_handler(function ($level, $message, $file, $line) {
                if (\Whoops\Util\Misc::isLevelFatal($level)) {
                    return;
                }

                return true;
            });
            $GLOBALS['__forme_whoops__'] = true;
        }
    }
}

if (!function_exists(__NAMESPACE__ . '\bootstrapDotenv')) {
    /**
     * Load dotenv if file exists and not already loaded.
     * forme.env takes precedence over .env.
     */
    function bootstrapDotenv(): void
    {
        $dotenvFile = file_exists(FORME_PRIVATE_ROOT . '.env') ? '.env' : null;
        $dotenvFile = file_exists(FORME_PRIVATE_ROOT . 'forme.env') ? 'forme.env' : $dotenvFile;
        if ($dotenvFile && !isset($GLOBALS['__forme_dotenv__'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(FORME_PRIVATE_ROOT, $dotenvFile);
            $dotenv->load();
            $GLOBALS['__forme_dotenv__'] = true;
        }
    }
}

// deprecated
if (!function_exists(__NAMESPACE__ . '\loadWhoops')) {
    /**
     * Load Whoops if dev and not already loaded.
     *
     * @deprecated use bootstrapWhoops() instead
     *
     **/
    function loadWhoops(): void
    {
        bootstrapWhoops();
    }
}

if (!function_exists(__NAMESPACE__ . '\loadDotenv')) {
    /**
     * Load dotenv if file exists and not already loaded.
     * forme.env takes precedence over .env.
     *
     * @deprecated use bootstrapDotenv() instead
     */
    function loadDotenv(): void
    {
        bootstrapDotenv();
    }
}

// convenience functions/getters

if (!function_exists(__NAMESPACE__ . '\getContainer')) {
    /**
     * Send back a new configured DI container
     * You probably only need this if you're outside of
     * the standard loader flow.
     */
    function getContainer(): ContainerInterface|FactoryInterface
    {
        if (isset($GLOBALS['__forme_container__'])) {
            return $GLOBALS['__forme_container__'];
        }

        return bootstrapContainer();
    }
}

if (!function_exists(__NAMESPACE__ . '\getInstance')) {
    /**
     * Get an instance of a class via the container.
     *
     * @return mixed
     */
    function getInstance(string $className)
    {
        $container = getContainer();

        return $container->get($className);
    }
}

if (!function_exists(__NAMESPACE__ . '\makeInstance')) {
    /**
     * Get a new instance of a class via the container (i.e. not singleton).
     *
     * @return mixed
     */
    function makeInstance(string $className)
    {
        $container = getContainer();

        return $container->make($className);
    }
}

if (!function_exists(__NAMESPACE__ . '\request')) {
    /**
     * Get the current request.
     */
    function request(): ServerRequestInterface|ServerRequest
    {
        return ServerRequest::fromRequest(ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        ));
    }
}

if (!function_exists(__NAMESPACE__ . '\log')) {
    /**
     * Log convenience function.
     */
    function log(): LoggerInterface
    {
        return getInstance(LoggerInterface::class);
    }
}

// utilities

if (!function_exists(__NAMESPACE__ . '\arrayKeysToCamelCase')) {
    /**
     * Convert array keys to camelCase
     * Useful for acf fields array (which is usually snake_case)
     * When it gets changed to scoped vars (which should be camelCase).
     */
    function arrayKeysToCamelCase(array $array): array
    {
        $arr = [];
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $key = (new UnicodeString($key))->camel()->toString();
            }

            if (is_array($value)) {
                $value = arrayKeysToCamelCase($value);
            }

            $arr[$key] = $value;
        }

        return $arr;
    }
}

if (!function_exists(__NAMESPACE__ . '\configExtract')) {
    function configExtract(string $constant, string $configPath): mixed
    {
        if (!file_exists($configPath)) {
            throw new InvalidArgumentException('File does not exist at specified path.');
        }

        $contents = file_get_contents($configPath);

        if ($constant === 'table_prefix') {
            $found = preg_match("/\\\$table_prefix.+?'(.+?)'.+/", $contents, $result);
        } else {
            $found = preg_match("/define.+?'" . $constant . "'.+?'(.*?)'.+/", $contents, $result);
        }

        if ($found) {
            return $result[1];
        }

        return null;
    }
}
