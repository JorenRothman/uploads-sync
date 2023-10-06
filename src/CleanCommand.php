<?php

namespace Joren\UploadsSync;

use WP_CLI;

class CleanCommand
{
    /**
     * Files instance.
     *
     * @var Files
     */
    private $files;

    public function __construct()
    {
        $this->files = new Files;
    }

    public function __invoke($args)
    {
        WP_CLI::confirm('Are you sure you want to start uploads clean?');
        WP_CLI::warning(WP_CLI::colorize('%RThis is a DESTRUCTIVE command%n'));

        $this->start();
    }

    private function start()
    {
        $files = $this->files->getFiles(false);

        if (empty($files)) {
            WP_CLI::warning('No resized files found.');
            return;
        }

        $count = count($files);
        WP_CLI::confirm('You are about to delete ' . $count . ' resized files. Are you sure?');
        $progress = WP_CLI\Utils\make_progress_bar('Deleting files', $count);

        WP_CLI::line("Found $count resized files.");

        foreach ($files as $file) {
            $this->deleteFile($file);
            $progress->tick();
        }

        $progress->finish();
        WP_CLI::success('All ' . $count  . ' resized files have been deleted.');
    }

    private function deleteFile($file)
    {
        if (!unlink($file)) {
            WP_CLI::error("Error deleting file: $file");
        }
    }
}
