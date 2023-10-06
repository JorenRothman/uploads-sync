<?php

namespace Joren\UploadsSync;

use WP_CLI;

class SyncCommand
{
    /**
     * Files class.
     *
     * @var Files
     */
    private $files;

    /**
     * WordPress class.
     *
     * @var WordPress
     */
    private $wordPress;

    /**
     * Whether to show verbose output.
     *
     * @var bool
     */
    private $isVerbose = false;

    public function __construct()
    {
        $this->files = new Files();
        $this->wordPress = new WordPress();
    }

    /**
     * Run the command.
     *
     * @param string[] $args
     * @return void
     */
    public function __invoke($args)
    {
        // Check if the user is sure.
        // If n is entered, the script will stop.
        WP_CLI::confirm('Are you sure you want to start uploads-sync?');

        if (isset($args[0]) && $args[0] === '-v') {
            $this->isVerbose = true;
        }

        $this->start();
    }

    /**
     * Start syncing.
     *
     * @return void
     */
    public function start()
    {
        // Tell user that script is starting.
        $this->verboseMessage('Starting uploads-sync...');

        // Get all files.
        $files = $this->files->getFiles();

        $fileCount = count($files);


        // Tell user that files were found.
        WP_CLI::line('Found ' . $fileCount . ' files.');

        if ($fileCount === 0) {
            // Tell user that no files were found.
            WP_CLI::error('No files found.');

            return; // nothing to do, exit early.
        }

        if (!$this->isVerbose) {
            // Create progress bar.
            $progress = WP_CLI\Utils\make_progress_bar('Processing files', $fileCount);
        }

        try {
            // Loop through all files.
            foreach ($files as $file) {
                // Tell user which file is being processed.
                $this->verboseMessage('Processing ' . $file);

                // Upload file.
                $success = $this->wordPress->insertAttachment($file);

                if (!$this->isVerbose) {
                    $progress->tick();
                }

                if (!$success) {
                    // Tell user that file was not processed.
                    $this->verboseMessage('File already exists ' . $file);

                    continue;
                }

                // Tell user that file was processed.
                $this->verboseMessage('Processed ' . $file);
            }
        } catch (\Exception $e) {
            // Tell user that an error occurred.
            WP_CLI::error('An error occurred.');
        }

        if (!$this->isVerbose) {
            $progress->finish();
        }

        // Tell user that script is done.
        WP_CLI::success('Done!');

        // Print donation message.
        WP_CLI::line('If you like this plugin, please consider donating: https://www.paypal.com/donate/?hosted_button_id=SJVH7EK43F5L8');
    }

    /**
     * Print a message if verbose is enabled.
     *
     * @param string $message
     * @return void
     */
    private function verboseMessage($message)
    {
        if ($this->isVerbose) {
            WP_CLI::line($message);
        }
    }
}
