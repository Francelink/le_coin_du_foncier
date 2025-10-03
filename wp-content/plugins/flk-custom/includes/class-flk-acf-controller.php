<?php

/**
 * Setup Class
 * read : https://www.php.net/manual/fr/language.oop5.php
 * Class qui va gérer le CRUD des inputs des offres 
 *
 */
defined('ABSPATH') || exit;

/**
 * On déclare la class si elle est existe
 *
 */
if (class_exists('FLK_Acf_Controller', false)) {
    return new FLK_Acf_Controller();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_Acf_Controller{
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