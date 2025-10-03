<?php

/**
 * Setup Metabox
 * Doc : 
 * https://developer.wordpress.org/reference/functions/add_meta_box/
 *
 */
defined('ABSPATH') || exit;

/**
 * On déclare la class si elle est existe
 *
 */
if (class_exists('FLK_Admin_Metabox', false)) {
    return new FLK_Admin_Metabox();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_Admin_Metabox
{
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
        //add_action('add_meta_boxes', array($this, 'add_flk_metabox_exemple'));
    }

    /**
     * * Add metabox.
     */
    public function add_flk_metabox_exemple()
    {
        // Add Metabox
        // add_meta_box(
        //     'flk_manage_stock',
        //     'Gestion des stocks',
        //     array($this, 'flk_metabox_exemple'),
        //     'product',
        //     'normal',
        //     'default'
        // );    
    }

    /**
     * Déclaration des méthodes
     *
     */
    public static function flk_metabox_exemple()
    {
        // Do some magics tricks
    }
}
