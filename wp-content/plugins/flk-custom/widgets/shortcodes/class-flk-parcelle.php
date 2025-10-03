<?php
/**
 * Shortcode pour afficher le groupe de champs => Parcelle
 */

defined('ABSPATH') || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Parcelle
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
                    "id" => "field_62eb9e4c0fb76", 
                    "name" => "ajouter_une_parcelle"
                ), 
                array(
                    "id" => "field_62ebb6fe13632", 
                    "name" => "terrain"
                ),
                array(
                    "id" => "field_62ebb79d92dd5", 
                    "name" => "vigne_et_vin"
                ),
                array(
                    "id" => "field_62ebbbed58c63", 
                    "name" => "complements"
                ),
            );
            $default_value = true; 
        } else {
            $id = 1185; // article de base pour récuperer les champs 
            $sous_groupe_array = array(
                array(
                    "id" => "ajouter_une_parcelle", 
                    "name" => "ajouter_une_parcelle"
                ), 
                array(
                    "id" => "terrain", 
                    "name" => "terrain"
                ),
                array(
                    "id" => "vigne_et_vin", 
                    "name" => "vigne_et_vin"
                ),
                array(
                    "id" => "complements", 
                    "name" => "complements"
                ),
            );
            // $sous_groupe_array = array(
            //     "ajouter_une_parcelle", 
            //     "terrain", 
            //     "vigne_et_vin", 
            //     "complements"
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
        FLK_Render_template::renderTemplate('widgets/shortcodes/parcelle', $data);
    }
}
