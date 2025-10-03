<?php

/**
 * Setup BDD request.
 *
 */

defined('ABSPATH') || exit;

if (class_exists('FLK_Bdd_request', false)) {
    return new FLK_Bdd_request();
}

/**
 * FLK_Bdd_Request Class.
 */

class FLK_Bdd_request
{
    /**
     * The flk_query function .
     * 
     * Utilise la function wpdb::get_results( string $query = null, string $output = OBJECT )
     * Détails https://developer.wordpress.org/reference/classes/wpdb/get_results/;
     * 
     * ToDo : 
     * $request = type string => requete demandé
     * $array = type Boolean => array (true) ou object (false)
     */

    public static function flk_query($request, $array = true)
    {
        global $wpdb;
        // On vérifie si la réponse doit renvoyer un array ou un object 
        if ($array) {
            $results = $wpdb->get_results($request, ARRAY_A);
        } else {
            $results = $wpdb->get_results($request);
        }
        // On retroune la réponse de la requete 
        return $results;
    }

    public static function flk_insert($table, $data)
    {
        global $wpdb;
        // $wpdb->show_errors();
        // wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
        $results = $wpdb->insert($table, $data);
        // On retroune la réponse de la requete
        return $results;
    }

    public static function flk_delete($table, $data)
    {
        // wpdb::delete( 'table', array( 'ID' => 1 ) )
        global $wpdb;                           // WPDB class object 
        return $wpdb->delete(
            $table,
            $data
        );
    }
}
