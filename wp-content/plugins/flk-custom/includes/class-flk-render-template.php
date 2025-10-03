<?php

/**
 * FLK_Render_template
 *! Modification Willy 
 *!  /wp-content/plugins/timber-library/vendor/twig/twig/src/Loader/FilesystemLoader.php
 *!   if (!is_dir($checkPath) && $checkPath !== '/') {
 *!    throw new LoaderError(sprintf('The "%s" directory does not exist ("%s").', $path, $checkPath));
 *!   }
 *
 */

defined('ABSPATH') || exit;

if (class_exists('FLK_Render_template', false)) {
    // var_dump('ok2');
    return new FLK_Render_template();
}

class FLK_Render_template
{
    /**
     * Render template
     * On affiche la template passé en paramètre,
     ** $name = chemin relative vers le fichier depuis templates/ 
     ** $data = data utilisé dans la template @array
     */

    public static function renderTemplate($name, $data = array())
    {
        $file = FLK_TEMPLATE_DIR . '/' . $name . '.twig';
        if (file_exists($file)) {
            Timber::$locations = FLK_TEMPLATE_DIR;
            return Timber::render($name . '.twig', $data);
        } else {
            echo "Le fichier $file n'existe pas.";
        }
    }
}
