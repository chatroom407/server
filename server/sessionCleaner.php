<?php

//Dodać do korna ??
//Czy odpalać jako odzielny proces lub watek serverHttp
$folder = __DIR__ . '/sessions'; 

if (is_dir($folder)) {
    $files = scandir($folder); 

    foreach ($files as $file) {
        $filePath = $folder . '/' . $file;

        if ($file == '.' || $file == '..') {
            continue;
        }

        if (is_file($filePath)) {
            $fileModificationTime = filemtime($filePath);
            $currentTime = time();

            if (($currentTime - $fileModificationTime) > 10) {
                echo "File $file older than 10s.\n";
            } else {
                echo "File $file is okay.\n";
            }
        }
    }
} else {
    echo "Dir $folder not exist.";
}
?>
