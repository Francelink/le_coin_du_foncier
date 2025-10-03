<?php

/**
 * Setup Class
 * read : https://www.php.net/manual/fr/language.oop5.php
 * A copier coller pour la création d'une classe 
 *
 */
defined('ABSPATH') || exit;

/**
 * On déclare la class si elle est existe
 *
 */
if (class_exists('FLK_Class_Exemple', false)) {
    return new FLK_Class_Exemple();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_Class_Exemple{
    /**
     * Déclaration d'une propriété
     *
     */

    // 
    public $var = 'une valeur par défaut';

    /**
     * Constructeurs
     *
     */
    public function __construct()
    {
    }

    /**
     * Déclaration des méthodes
     *
     */
    public static function TestFunction()
    {
        // Do some magics tricks
    }

    
 }