<?php

/**
 * Setup Hook.
 * 
 * Docs:
 * https://developer.wordpress.org/reference/functions/add_action/
 * https://developer.wordpress.org/reference/functions/add_filter/
 * 
 */

defined('ABSPATH') || exit;

if (class_exists('FLK_Hooks', false)) {
    return new FLK_Hooks();
}

/**
 * FLK_Hooks Class.
 */

class FLK_Hooks
{
    /**
     * Constructor
     * Pour appeler le Hook dans un autre fichier 
     * do_action('flk_nom_du_hook', $arg) ou apply_filters( 'flk_nom_du_hook', $arg );
     */
    public function __construct()
    {
        add_filter('body_class',array($this, 'addCatToPost')); // on ajoute la catégorie au body du post
        add_action('elementor_pro/forms/actions/register', array($this, 'registerAction') ); // on enregistre l'action d'envoie des infos de l'offre par mail 
        add_action('init', array($this, 'FLKSession')); 
        // on passe les variables de sessions dans le DOM 
        add_action('wp_footer', array($this, 'FLKSessionPushToDOM'));
        // on change le name des mails 
        apply_filters( 'wp_mail_from_name', array($this, 'FLKChangeEmailSenderName') );
        //add_action('flk_nom_du_hook', array($this, 'flk_hook_exemple'));
    }

     /**
     * Function 
     * correspondante au hook 'flk_nom_du_hook
     */
    public static function addCatToPost($classes)
    {

        if (is_single() ) {
            global $post;
            foreach((get_the_category($post->ID)) as $category) {
              // add category slug to the $classes array
              $classes[] = "flk_categorie_" . $category->category_nicename;
            }
            $statut = get_post_status( $post );
            $classes[] = "flk_statut_" . $statut;
          }
          // return the $classes array
          return $classes;
    }

    public static function registerAction( $form_actions_registrar )
    {
        $form_actions_registrar->register( new FLK_Action_Obtenir_Les_Infos() );
    }

    public static function FLKSession()
    {
        session_start();
        // if( ! session_id() ) { 
        //     session_start(); 
        // } 
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
    }

    public static function FLKSessionPushToDOM()
    {
        session_start();
        if ( isset( $_SESSION['data_flk_offre_parent_enfant'] ) ) {
            $data_decode = json_decode($_SESSION['data_flk_offre_parent_enfant'], true);
            echo '<input type="hidden" name="sessionElementCat" value="' . $data_decode['enfant']['categorie'] .'">';
            foreach ($data_decode["enfant"]["liste_des_enfants"] as $key => $value) {
                echo '<input type="hidden" name="sessionElementEnfant" value="' . $value .'">';
            }
        }
    }

    public static function FLKChangeEmailSenderName($original_email_from)
    {
        return 'Le coin du foncier';
    }

}
