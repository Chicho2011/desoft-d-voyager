<?php

namespace Desoft\DVoyager\Utils;

class GeneratorUtilities {

    public static function createFile(string $path, string $body, string $mode = 'w')
    {
        $newFile = fopen($path, $mode);
        fwrite($newFile, $body);
        fclose($newFile); 
    }

}