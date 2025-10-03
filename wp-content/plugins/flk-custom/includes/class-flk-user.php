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
if (class_exists('FLK_User', false)) {
    return new FLK_User();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_User{
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


    public static function user_has_role($user_id, $role_name)
    {
        if (  $user_id !== 0 ) {
            $user_meta = get_userdata($user_id);
            $user_roles = $user_meta->roles;
            return in_array($role_name, $user_roles);
        } else {
            return false;
        }
    }
    
 }