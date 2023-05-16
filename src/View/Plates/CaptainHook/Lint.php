<?php

namespace Forme\Framework\View\Plates\CaptainHook;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use Exception;
use Forme\Framework\View\Plates\Exception\TemplateErrorException;
use Forme\Framework\View\Plates\Validator;
use SebastianFeldmann\Git\Repository;

class Lint implements Action
{
    /**
     * Executes the action.
     *
     * @param \CaptainHook\App\Config\Action $action
     *
     * @throws Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        // we have to provide a custom filter because we do not want to check any deleted files
        $changedPlateFiles  = $repository->getIndexOperator()->getStagedFilesOfType('php', ['A', 'C', 'M']);
        $changedPlateFiles  = array_filter($changedPlateFiles, fn ($file) => str_ends_with($file, 'plate.php'));

        $directory = dirname($config->getPath());

        $failedFiles        = [];
        $messages           = [];

        foreach ($changedPlateFiles as $file) {
            $prefix = IOUtil::PREFIX_OK;
            if ($this->hasValidationErrors($directory . '/' . $file)) {
                $prefix        = IOUtil::PREFIX_FAIL;
                $failedFiles[] = $directory . '/' . $file;
            }
            $messages[] = $prefix . ' ' . $file;
        }

        $io->write(['', '', implode(PHP_EOL, $messages), ''], true, IO::VERBOSE);

        $failedFilesCount = count($failedFiles);

        if ($failedFilesCount > 0) {
            throw new ActionFailed('Linting failed: View template errors in ' . $failedFilesCount . ' file(s)' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $failedFiles));
        }
    }

    /**
     * Lint a php file.
     */
    protected function hasValidationErrors(string $file): bool
    {
        try {
            Validator::validateFile($file);

            return false;
        } catch (TemplateErrorException $e) {
            return true;
        }
    }
}
