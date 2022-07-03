<?php

function getFiles(string $path): array {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . "/../$path"));
    $files = []; 
    foreach ($rii as $file)
        if (!$file->isDir())
            $files[] = $file->getPathname();
    return $files;
}