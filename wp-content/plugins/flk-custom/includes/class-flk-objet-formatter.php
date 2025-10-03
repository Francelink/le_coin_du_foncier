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
if (class_exists('FLK_Objet_Formatter', false)) {
    return new FLK_Objet_Formatter();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_Objet_Formatter
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
    }

    /**
     * On retourne les objets pour créer les inputs en front
     *
     */
    public static function getInputObject($sub_filed_objet, $default_value = false)
    {
        // Si le placeholder existe
        if (array_key_exists("placeholder", $sub_filed_objet)) {
            $placeholder = $sub_filed_objet["placeholder"];
        } else {
            $placeholder = " ";
        }
        // si les options existe
        if (array_key_exists("choices", $sub_filed_objet)) {
            $option = true;
            $options_value = $sub_filed_objet["choices"];
            uasort($options_value, array("FLK_Objet_Formatter", "sort_by_name") );
        } else {
            $option = false;
            $options_value = false;
        }
        // on gère le required
        if (array_key_exists("required", $sub_filed_objet)) {
            if ($sub_filed_objet["required"] === 1) {
                $required = true;
            } else {
                $required = false;
            }
        } else {
            $required = false;
        }
        return array(
            "class" => 'input', // todo add group
            "label" => $sub_filed_objet["label"],
            "name" => $sub_filed_objet["_name"],
            "type" => $sub_filed_objet["type"],
            "order" => $sub_filed_objet["menu_order"],
            "option" => $option,
            "options_value" => $options_value,
            "id" => $sub_filed_objet["ID"],
            "data_key" => $sub_filed_objet["key"],
            "required" => $required,
            "placeholder" => $placeholder,
            "instructions" => $sub_filed_objet["instructions"],
            "classes" => $sub_filed_objet["wrapper"]["class"],
            "default_value" => $default_value,
        );
    }


    /**
     * On retourne la nature de l'article 
     *
     */
    public static function parcelleOrCuvage($post, $type = null) 
    {
        if ($type = null ) {

        } else {

        }
    }


    public static function sort_by_name($a,$b)
    {
        return $a > $b;
    }
    

}
