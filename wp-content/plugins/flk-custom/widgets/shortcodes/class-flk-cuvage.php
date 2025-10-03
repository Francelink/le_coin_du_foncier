<?php
/**
 * Shortcode pour afficher le groupe de champs => Cuvage
 */

defined('ABSPATH') || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Cuvage
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
        if ( isset( $atts["id"] ) ) {
            $id = $atts["id"]; // article de base pour récuperer les champs 
            $sous_groupe_array = array(
                array(
                    "id" => "field_62ebbd17e30df", 
                    "name" => "lieu"
                ), 
                array(
                    "id" => "field_62ebbd8571422", 
                    "name" => "cuvage"
                ),
                array(
                    "id" => "field_62ebbfabfdd11", 
                    "name" => "complement"
                ),
            );
            // $sous_groupe_array = array(
            //     "field_62ebbd17e30df",
            //     "field_62ebbd8571422",
            //     "field_62ebbfabfdd11",
            // );
            $default_value = true; 
        } else {
            $id = 1261; // article de base pour récuperer les champs
            $sous_groupe_array = array(
                array(
                    "id" => "lieu", 
                    "name" => "lieu"
                ), 
                array(
                    "id" => "cuvage", 
                    "name" => "cuvage"
                ),
                array(
                    "id" => "complement", 
                    "name" => "complement"
                ),
            );
            // $sous_groupe_array = array(
            //     "lieu", 
            //     "cuvage", 
            //     "complement"
            // ); // liste des groupes de champs pour l'ajout d'une parcelle  
            $default_value = false;
        }
        $acf_groupe_object = get_field_objects($id);
        foreach ($sous_groupe_array as $key => $sous_groupe) {
            if (have_rows($sous_groupe["id"], $id)) { // Si le groupe de champ ajouter_une_parcelle existe
                if ( isset($acf_groupe_object[$sous_groupe["name"]]) ) {
                    $title = $acf_groupe_object[$sous_groupe["name"]]["label"]; // on récupère le nom du sous group 
                } else {
                    $title = NULL;
                }
                // $title = $acf_groupe_object[$sous_groupe]["label"]; // on récupère le nom du sous group 
                $data["inputs"][$sous_groupe["name"]] = array(
                    'titre' => $title,
                    'champs' => array()
                ); // on crée un array pour envoyé les champs par sous groupe vers le front 
                $row = the_row();
                $layout = get_row_layout();
                foreach ($row as $key => $field) { // on récupère les infos de chaque champs du sous groupe 
                    $sub_filed_objet = get_sub_field_object($key);
                    /**
                     * @see  FLK_Objet_Formatter::getInputObject()
                    */
                    if ( $default_value ) {
                        $data["inputs"][$sous_groupe["name"]]['champs'][$sub_filed_objet["menu_order"]]= FLK_Objet_Formatter::getInputObject($sub_filed_objet, $field); // on push les info du champs 
                    } else {
                        $data["inputs"][$sous_groupe["name"]]['champs'][$sub_filed_objet["menu_order"]]= FLK_Objet_Formatter::getInputObject($sub_filed_objet); // on push les info du champs 
                    }
                }
            };
        }
        FLK_Render_template::renderTemplate('widgets/shortcodes/cuvage', $data);
    }
}
