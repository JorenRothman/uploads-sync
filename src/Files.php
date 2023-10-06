<?php

namespace Joren\UploadsSync;

class Files
{
    /**
     * Get all files from the uploads directory.
     *
     * @return array
     */
    public function getFiles($isOriginal = true)
    {
        $files = [];

        $uploads = wp_upload_dir();

        $files = $this->getFilesFromDir($uploads['basedir'], $isOriginal);

        return $files;
    }

    /**
     * Get all files from a directory.
     *
     * @param string $dir
     * @return array
     */
    private function getFilesFromDir($dir, $isOriginal = true)
    {
        $files = [];

        $dir = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($dir);

        foreach ($iterator as $file) {
            if ($file->isFile() && $this->isOriginalFile($file->getPathname(), $isOriginal) && $this->isYearFolder($file->getPath())) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Check if the folder is a year folder.
     *
     * @param string $folder
     * @return bool
     */
    private function isYearFolder($folder)
    {
        // match the pattern 2023
        return preg_match('/\d{4}/', $folder) === 1;
    }

    /**
     * Check if the file is an original file.
     *
     * @param string $file
     * @return bool
     */
    private function isOriginalFile($file, $isOriginal = true)
    {

        // match the pattern -123x123.ext
        return preg_match('/-\d+x\d+\./', $file) === intval(!$isOriginal);
    }
}
