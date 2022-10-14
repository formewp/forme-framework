<?php
declare(strict_types=1);

namespace Forme\Framework\Database;

use Forme\Framework\Core\PluginOrThemeable;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class Migrations
{
    use PluginOrThemeable;

    /**
     * @var string
     */
    public const DB_DIR = '/app/Database/Migrations';

    /** @var TextWrapper */
    protected $phinxApplication;

    /** @var string */
    protected $phinxConfigLocation;

    public function __construct(PhinxApplication $phinxApplication, protected LoggerInterface $logger, protected MigrationInitialiser $initialiser)
    {
        // wrap Phinx in the textwrapper as per https://github.com/cakephp/phinx/blob/master/app/web.php
        $this->phinxConfigLocation = FORME_PRIVATE_ROOT . 'phinx.yml';
        $this->phinxApplication    = new TextWrapper($phinxApplication, ['configuration' => $this->phinxConfigLocation]);
    }

    public function migrate(): array
    {
        $messages = [];
        // this should run on plugin/theme install or on upgrade
        // create phinx.yml if it doesn't exist yet
        $this->initialiser->maybeCreate($this->phinxConfigLocation);
        // set the db environments
        $this->initialiser->setEnvironments($this->phinxConfigLocation);
        // grab the current config and save before changes to check for diff
        $phinxConfig    = Yaml::parse(file_get_contents($this->phinxConfigLocation));
        $originalConfig = $phinxConfig;
        // make sure this framework directory _and_ the plugin/theme migration directories are in
        // adding plugins/themes at different points should be ok because older migrations will still run
        $migrationPaths = $phinxConfig['paths']['migrations'];
        if (!is_array($migrationPaths)) {
            // assume this has just been created, so let's blank it
            $migrationPaths = [];
            $this->logger->info('Initialising migration paths');
        }

        // add plugin/theme paths if not in
        if (self::isPlugin() && !in_array(self::getPluginPath() . self::DB_DIR, $migrationPaths)) {
            $migrationPaths[] = self::getPluginPath() . self::DB_DIR;
            $this->logger->info('Adding plugin migration path');
        } elseif (!self::isPlugin() && !in_array(self::getThemePath() . self::DB_DIR, $migrationPaths)) {
            $migrationPaths[] = self::getThemePath() . self::DB_DIR;
            $this->logger->info('Adding theme migration path');
        }

        // add framework path if not in
        if (!in_array(__DIR__ . '/Migrations', $migrationPaths)) {
            $migrationPaths[] = __DIR__ . '/Migrations';
            $this->logger->info('Adding framework migration path');
        }

        $phinxConfig['paths']['migrations'] = $migrationPaths;
        // save to phinx.yml if things have changed
        if ($phinxConfig !== $originalConfig) {
            $yaml = Yaml::dump($phinxConfig);
            file_put_contents($this->phinxConfigLocation, $yaml);
            $messages[] = ['type' => 'success', 'text' => 'Forme phinx.yml saved, check ' . $this->phinxConfigLocation];
            $this->logger->info('phinx.yml saved after path changes');
        }

        $this->logger->info('Migration path config completed');
        // run migrations
        $output = $this->phinxApplication->getMigrate(WP_ENV ?: 'development');
        $this->logger->info($output);
        $messages[] = ['type' => 'success', 'text' => 'Forme Migration completed, check server logs for more info'];

        return $messages;
    }

    /**
     * CAREFUL!!! This method rolls back all forme migrations and thereby borks your database state. You probably only want this in a testing environment.
     */
    public function rollback(): array
    {
        $messages = [];
        // run migrations
        $output = $this->phinxApplication->getRollback(WP_ENV ?: 'testing', 0);
        $this->logger->info($output);
        $messages[] = ['type' => 'success', 'text' => 'Forme Rollback completed, check server logs for more info'];

        return $messages;
    }
}
