<?php
/**
 * Exemple de fichier php pour la création d'un shortcode
 */

defined('ABSPATH') || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Print_Votre_Offre {

    /**
     * Output the Shortcode Exemplee.
     *
     * @param array $atts Shortcode attributes.
     */
    public static function output($atts) {
        $data = array();
        global $post;
        $data           = array();
        $data["inputs"] = array();
        /// on récupère le statut de l'article
        $data["statut"] = get_post_status($post);
        if (have_rows('field_62e10cad1039f', $post->ID)) {
            $row = the_row();
            foreach ($row as $key => $field) {
                if ($field !== "vigne" && $field !== "cuvage" && $field !== "" && $field !== "0" && $field !== false) {
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
                    case 'select':
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
                        break;
                    }
                    //var_dump($data["inputs"][$sub_field_objet["menu_order"]]);
                }
            }
        } else { // todo logs no rows found

        };
        FLK_Render_template::renderTemplate('widgets/shortcodes/print-votre-offre', $data);
    }
}
