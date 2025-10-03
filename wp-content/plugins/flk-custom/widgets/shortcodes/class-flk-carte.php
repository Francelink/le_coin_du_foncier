<?php
/**
 * Exemple de fichier php pour la création d'un shortcode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Carte {

	/**
	 * Output the Shortcode Exemplee.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts ) {
		$data = array();
		FLK_Render_template::renderTemplate('widgets/shortcodes/carte', $data);
	}
}
