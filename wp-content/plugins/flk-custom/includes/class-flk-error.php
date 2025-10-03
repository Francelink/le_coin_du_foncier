<?php

/**
 * FLK Error generate file
 */

defined('ABSPATH') || exit;

if (class_exists('FLK_Error', false)) {
    // var_dump('ok2');
    return new FLK_Error();
}

class FLK_Error
{
    /**
     ** FLK Error generate file
     */

    public static function generateErrorFile($text, $file)
    {
        $file = FLK_PLUGIN_LOG_DIR . '/' . $file . FLK_PLUGIN_FILE_FORMAT;
        if (file_exists($file)) {
            file_put_contents($file, PHP_EOL . $text . PHP_EOL,  FILE_APPEND | LOCK_EX);
        } else {
            echo "Le fichier $file n'existe pas.";
        }
    }
}
