<?php

namespace Joren\UploadsSync;

class WordPress
{
    /**
     * Uploads directory.
     *
     * @var array
     */
    private $uploadsDir;

    public function __construct()
    {
        $this->uploadsDir = wp_upload_dir();
    }

    /**
     * Upload a file to the database.
     *
     * @param string $path
     * @return void
     */
    public function insertAttachment($path)
    {
        $file = $this->getFileDetails($path);

        if ($this->isAlreadyInDB($file['name'])) {
            return false;
        }

        $args = [
            'guid' => $file['guid'],
            'post_mime_type' => $file['mime_type']['type'],
            'post_title' => $file['name'],
            'post_content' => '',
            'post_date' => $file['upload_date'],
            'post_status' => 'inherit'
        ];

        $attachmentId = wp_insert_attachment($args, $path);

        if (is_wp_error($attachmentId)) {
            throw new \Exception($attachmentId->get_error_message());
        }

        $this->generateAttachmentMetadata($attachmentId, $path);

        return true;
    }


    /**
     * Get the file details.
     *
     * @param string $path
     * @return array
     */
    private function getFileDetails($path)
    {
        $date = $this->getFileDate($path);

        $file = [
            'guid' => $this->uploadsDir['url'] . '/' . basename($path),
            'name' => basename($path),
            'mime_type' => wp_check_filetype($path, null),
            'upload_date' => $date,
        ];

        return $file;
    }

    /**
     * Get the file date.
     *
     * @param string $path
     * @return string
     */
    private function getFileDate($path)
    {
        $segments = explode('/', $path);
        $year = $segments[count($segments) - 3];
        $month = $segments[count($segments) - 2];

        return $year . '-' . $month . '-01 00:00:00';
    }

    /**
     * Check if the file is already in the database.
     *
     * @param string $fileName
     * @return bool
     */
    private function isAlreadyInDB($fileName)
    {
        global $wpdb;

        $page = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title = '" . $fileName . "' AND post_type = 'attachment'");

        return !!$page;
    }

    /**
     * Generate attachment metadata.
     *
     * @param int $attachmentId
     * @param string $file
     * @return void
     */
    private function generateAttachmentMetadata($attachmentId, $file)
    {
        $metadata = wp_generate_attachment_metadata($attachmentId, $file);

        wp_update_attachment_metadata($attachmentId, $metadata);
    }

    public function filterExistingFiles($files)
    {
        global $wpdb;

        $databaseEntries = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_type = 'attachment'");

        $databaseEntries = array_map(function ($entry) {
            return $entry->post_title;
        }, $databaseEntries);

        return array_filter($files, function ($file) use ($databaseEntries) {
            return !in_array(basename($file), $databaseEntries);
        });
    }

    public function getAllDatabaseAttachmentIDs()
    {
        global $wpdb;

        $databaseEntries = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment'");

        return array_map(function ($entry) {
            return $entry->ID;
        }, $databaseEntries);
    }
}
