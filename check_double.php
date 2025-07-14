<?php

function removeDuplicateFiles($directory) {
    if (!is_dir($directory)) {
        echo "The specified directory does not exist.";
        return;
    }

    $crcArray = [];
    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = $directory . DIRECTORY_SEPARATOR . $file;

        if (is_file($filePath)) {
            $crc = hash_file('crc32', $filePath);

            if (isset($crcArray[$crc])) {
                $existingFilePath = $crcArray[$crc];

                if (filemtime($filePath) > filemtime($existingFilePath)) {
                    echo "Removing older duplicate: $existingFilePath\n";
                    unlink($existingFilePath);
                    $crcArray[$crc] = $filePath;
                } else {
                    echo "Removing older duplicate: $filePath\n";
                    unlink($filePath);
                }
            } else {
                $crcArray[$crc] = $filePath;
            }
        }
    }

    echo "Scanning completed.\n";
}

$directoryPath = __DIR__ . "/scan_folder";
removeDuplicateFiles($directoryPath);
?>