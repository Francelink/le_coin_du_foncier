<?php
/**
 * Exemple de fichier php pour la création d'un shortcode
 */

defined('ABSPATH') || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Print_Votre_Profil {

    /**
     * Output the Shortcode Exemplee.
     *
     * @param array $atts Shortcode attributes.
     */
    public static function output($atts) {
        global $post;
        $data           = array();
        $data["inputs"] = array();
        
        
        /// on récupère le statut de l'article
        $data["statut"] = get_post_status($post);

        if (have_rows('field_62e10938f0866')) {
            $row       = the_row();
            $sub_value = the_field('votre_nom');
            foreach ($row as $key => $field) {
                $sub_field_objet = get_sub_field_object($key);
                $sub_field_value = get_sub_field($key);
                $type            = $sub_field_objet["type"];
                switch ($type) {
                case 'radio':
                    $data["inputs"][$sub_field_objet["menu_order"]] = array(
                        "name"  => $field,
                        "label" => $sub_field_objet["label"],
                        "value" => $sub_field_objet["choices"][$field],
                    );
                    break;
                case 'checkbox':
                    $data["inputs"][$sub_field_objet["menu_order"]] = array(
                        "name"  => $field,
                        "label" => $sub_field_objet["label"],
                        "value" => $sub_field_objet["choices"][$field],
                    );
                    break;
                default:
                    $data["inputs"][$sub_field_objet["menu_order"]] = array(
                        "name"  => $field,
                        "label" => $sub_field_objet["label"],
                        "value" => $field,
                    );
                };
            }
            //var_dump($data["inputs"]);
        } else { // todo logs no rows found

        };

        /*if ( is_user_logged_in() ) {
                var_dump('Admin', is_user_logged_in());
                
             } else {
               var_dump('pas Admin');
             }
             return is_user_logged_in();
             /*$login = function() {
                if ( is_user_logged_in() ) {
                    return '<a class="header__login-link" href="/wp-login.php?                action=logout&_wpnonce=6e24015e99">Log Out »</a>';
                } else {
                    return ' <a class="header__login-link" href="/login/">Log In »</a>';
                }
            };*/

        $user_is_subscriber = FLK_User::user_has_role(get_current_user_id(), 'administrator');
    
        $data['is_admin'] = $user_is_subscriber;
        //var_dump($data['is_admin']);die;


        FLK_Render_template::renderTemplate('widgets/shortcodes/print-votre-profil', $data);
    }
}
