<?php

namespace App\Services;

class FileService
{
    /**
     * Process data and save file in a provided path.
     * @return void
     */
    public function savePublicFile($filePath, $reportContent): void
    {
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        file_put_contents($filePath, $reportContent);
    }
}