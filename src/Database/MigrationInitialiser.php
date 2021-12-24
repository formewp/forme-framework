<?php
declare(strict_types=1);

namespace Forme\Framework\Database;

use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class MigrationInitialiser
{
    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $defaultConfigLocation;

    public function __construct(LoggerInterface $logger)
    {
        $this->defaultConfigLocation = realpath(__DIR__ . '/phinx.default.yml');
        $this->logger                = $logger;
    }

    /**
     * Creates a phinx file if it doesn't exist yet.
     */
    public function maybeCreate(string $configLocation): void
    {
        if (!file_exists($configLocation)) {
            // grab the default
            $defaultConfig = Yaml::parse(file_get_contents($this->defaultConfigLocation));
            // save the config
            $yaml = Yaml::dump($defaultConfig);
            file_put_contents($configLocation, $yaml);
            $this->logger->info('Creating phinx file at ' . $configLocation);
        }
    }

    public function setEnvironments(string $configLocation): void
    {
        global $wpdb;

        $phinxConfig    = Yaml::parse(file_get_contents($configLocation));
        $originalConfig = $phinxConfig;
        // make sure the default environment matches the set environment, if wp_env not set then assume development
        $defaultEnvironment = $phinxConfig['environments']['default_environment'];
        if ($defaultEnvironment != WP_ENV) {
            $phinxConfig['environments']['default_environment'] = WP_ENV ?: 'development';
            $defaultEnvironment                                 = $phinxConfig['environments']['default_environment'];
            $this->logger->info('Setting the Phinx db environment to ' . $defaultEnvironment);
        }
        // if db credentials do not match, let's set them - host/port//name/user/pass/charset
        $dbCredentials = $phinxConfig['environments'][$defaultEnvironment];
        if (
            ($dbCredentials['host'] != DB_HOST && $dbCredentials['host'] . ':' . $dbCredentials['port'] != DB_HOST) ||
            $dbCredentials['name'] != DB_NAME ||
            $dbCredentials['user'] != DB_USER ||
            $dbCredentials['pass'] != DB_PASSWORD ||
            $dbCredentials['charset'] != DB_CHARSET
        ) {
            $hostPort                                         = explode(':', DB_HOST);
            $dbCredentials['host']                            = $hostPort[0];
            $dbCredentials['port']                            = isset($hostPort[1]) ? $hostPort[1] : 3306;
            $dbCredentials['name']                            = DB_NAME;
            $dbCredentials['user']                            = DB_USER;
            $dbCredentials['pass']                            = DB_PASSWORD;
            $dbCredentials['charset']                         = DB_CHARSET;
            $phinxConfig['environments'][$defaultEnvironment] = $dbCredentials;
            $this->logger->info('Updating the db credentials for Phinx');
        }
        // add db_prefix
        $dbPrefix = $wpdb->prefix ?: 'wp_';
        foreach (array_keys($phinxConfig['environments']) as $key) {
            if (substr($key, 0, 8) !== 'default_') {
                $phinxConfig['environments'][$key]['table_prefix'] = $dbPrefix;
            }
        }
        $this->logger->info('Adding db prefix ' . $dbPrefix);
        // save to phinx.yml if things have changed
        if ($phinxConfig !== $originalConfig) {
            $yaml = Yaml::dump($phinxConfig);
            file_put_contents($configLocation, $yaml);
            $this->logger->info('phinx.yml saved after env changes');
        }
    }
}
