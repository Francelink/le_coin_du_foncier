<?php
/**
 * Exemple de fichier php pour la création d'un shortcode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_searchFom {

	/**
	 * Output the Shortcode Exemplee.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts ) {
		 // todo Crée un article de test ?
        // On récupère le groupe de champs
        $data = array();
        $data["inputs"] = array();
        $id = 1451; // article de base pour récuperer les champs 
        $sous_groupe_array = array(
            "recherche", 
            "recherche_complement_vigne", 
            "recherche_complement_cuvage", 
        ); // liste des groupes de champs pour l'ajout d'une parcelle 
        $acf_groupe_object = get_field_objects($id);
        foreach ($sous_groupe_array as $key => $sous_groupe) {
            if (have_rows($sous_groupe, $id)) { // Si le groupe de champ ajouter_une_parcelle existe
                $title = $acf_groupe_object[$sous_groupe]["label"]; // on récupère le nom du sous group 
                $data["inputs"][$sous_groupe] = array(
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
                    $data["inputs"][$sous_groupe]['champs'][$sub_filed_objet["menu_order"]]= FLK_Objet_Formatter::getInputObject($sub_filed_objet); // on push les info du champs 
                }
            };
        }
        FLK_Render_template::renderTemplate('widgets/shortcodes/recherche', $data);
	}
}
