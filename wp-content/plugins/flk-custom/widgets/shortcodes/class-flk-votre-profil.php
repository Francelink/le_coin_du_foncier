<?php
/**
 * Shortcode pour afficher le groupe de champs => Votre Profil
 */

defined('ABSPATH') || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Votre_Profil
{

    /**
     * Output the Shortcode Exemplee.
     *
     * @param array $atts Shortcode attributes.
     */
    public static function output($atts)
    {
        // todo Crée un article de test ?
        // On récupère le groupe de champs
        $data = array();
        $data["inputs"] = array();
        if ( isset( $_SESSION['data_flk_offre_parent_enfant'] ) ) {
            $data_decode = json_decode($_SESSION['data_flk_offre_parent_enfant'], true);
            $id = $data_decode["id_parent_offre"];
            if ( have_rows('field_62e10938f0866', $id) ) {
                $row = the_row();
                $layout = get_row_layout();
                foreach ($row as $key => $field) {
                    $sub_filed_objet = get_sub_field_object($key);
                    $data["inputs"][$sub_filed_objet["menu_order"]] = FLK_Objet_Formatter::getInputObject($sub_filed_objet, $field);
                }
            };
        } else {
            if (have_rows('votre_profil', 1960)) {
                $row = the_row();
                $layout = get_row_layout();
                foreach ($row as $key => $field) {
                    $sub_filed_objet = get_sub_field_object($key);
                    $data["inputs"][$sub_filed_objet["menu_order"]] = FLK_Objet_Formatter::getInputObject($sub_filed_objet);
                }
            };
        }
        FLK_Render_template::renderTemplate('widgets/shortcodes/votre_profil', $data);
    }
}
