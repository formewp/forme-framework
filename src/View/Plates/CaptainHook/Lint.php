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
        $changedPlateFiles  = $repository->getIndexOperator()->getStagedFilesOfType('plate.php', ['A', 'C', 'M']);
        $failedFilesCount   = 0;
        $failedFiles        = [];
        $messages           = [];

        foreach ($changedPlateFiles as $file) {
            $prefix = IOUtil::PREFIX_OK;
            if ($this->hasValidationErrors($file)) {
                $prefix = IOUtil::PREFIX_FAIL;
                $failedFilesCount++;
            }
            $messages[] = $prefix . ' ' . $file;
        }

        $io->write(['', '', implode(PHP_EOL, $messages), ''], true, IO::VERBOSE);

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
