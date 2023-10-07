<?php

namespace Joren\UploadsSync;

use WP_CLI;

class CleanDBCommand
{
    /**
     * WordPress instance.
     *
     * @var WordPress
     */
    private $wordPress;

    public function __construct()
    {
        $this->wordPress = new WordPress;
    }

    public function __invoke($args)
    {
        WP_CLI::confirm('Are you sure you want to start cleaning the database?');
        WP_CLI::warning(WP_CLI::colorize('%RThis is a DESTRUCTIVE command%n'));

        $this->start();
    }

    private function start()
    {
        $ids = $this->wordPress->getAllDatabaseAttachmentIDs();

        if (empty($ids)) {
            WP_CLI::warning('No attachment\'s found.');
            return;
        }

        $count = count($ids);
        WP_CLI::confirm('You are about to delete ' . $count . ' attachment entries. Are you sure?');
        $progress = WP_CLI\Utils\make_progress_bar('Deleting entries', $count);

        WP_CLI::line("Found $count resized files.");

        foreach ($ids as $id) {
            $this->deleteEntry($id);
            $progress->tick();
        }

        $progress->finish();
        WP_CLI::success('All ' . $count  . ' attachment entries have been deleted.');
    }

    private function deleteEntry($id)
    {
        global $wpdb;

        $success = $wpdb->query("DELETE FROM $wpdb->posts WHERE ID = $id");

        if ($success) {
            $wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = $id");
        } else {
            WP_CLI::error("Error deleting entry: $id");
        }
    }
}
