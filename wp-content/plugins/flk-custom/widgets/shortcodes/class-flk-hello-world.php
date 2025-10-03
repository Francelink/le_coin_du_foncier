<?php
/**
 * Hello World Shortcode
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Hello_World {

	/**
	 * Output the Hello World shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts ) {
		FLK_Render_template::renderTemplate('widgets/shortcodes/flk-hello-world');
		$text = "Ceci est un test de log"; 
		FLK_Error::generateErrorFile($text, 'logs');
	}
}
