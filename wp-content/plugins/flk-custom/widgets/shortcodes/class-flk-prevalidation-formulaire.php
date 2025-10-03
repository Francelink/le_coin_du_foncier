<?php
/**
 * shortcode du formulaire de prévalidation
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_PrevalidationFom {

	/**
	 * Output the Shortcode Exemplee.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts ) {
		$data = array();
		$user = wp_get_current_user();
		$data['administrator'] = in_array( 'administrator', (array) $user->roles );
		//var_dump($user);
		FLK_Render_template::renderTemplate('widgets/shortcodes/formulaire_pre_validation', $data);
	}
}
