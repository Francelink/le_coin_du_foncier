<?php
/**
 * Exemple de fichier php pour la création d'un shortcode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_validationFom {

	/**
	 * Output the Shortcode Exemplee.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts ) {
		$data = array();
		$user = wp_get_current_user();
		$data['administrator'] = in_array( 'administrator', (array) $user->roles );
        global $post;
        $data["post"]["parent"]["id"] = $post->ID;
        $data["post"]["enfants"] = array();
        $request = "SELECT post_id FROM wp_postmeta WHERE meta_key='parent' AND meta_value='" . $post->ID ."'";
        $query = FLK_Bdd_request::flk_query($request);
        if ( count($query) > 0 ){ 
			foreach ($query as $k => $value) {
				// l'offre à des parcelles ou des cuvages        
				$enfant = $value["post_id"];
                array_push($data["post"]["enfants"], array(
                    'id' => $enfant,
                ));
			}
		} else { // l'offre n'à des parcelles ou des cuvages
			var_dump(0);
		}

		// On récupère les cases à cocher 
		$data['cases'] = array();
		if (have_rows('conditions', 1960)) {
			$row = the_row();
			$layout = get_row_layout();
			foreach ($row as $key => $field) {
				$sub_filed_objet = get_sub_field_object($key);
				$data["cases"] = FLK_Objet_Formatter::getInputObject($sub_filed_objet);
			}
		};
		FLK_Render_template::renderTemplate('widgets/shortcodes/formulaire_validation', $data);
	}
}
