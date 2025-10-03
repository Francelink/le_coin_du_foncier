<?php

/**
 * Shortcodes
 *
 */

defined('ABSPATH') || exit;

/**
 * FLK Shortcodes class.
 */
class FLK_Shortcodes
{

	/**
	 * Init shortcodes.
	 */
	public static function init()
	{
		// include files
		self::include();

		// add shortcode 
		$shortcodes = array(
			'flk_hello_world'  => __CLASS__ . '::Hello_World',
			'flk_votre_profil'  => __CLASS__ . '::Votre_Profil',
			'flk_votre_offre'  => __CLASS__ . '::Votre_Offre',
			'flk_parcelle'  => __CLASS__ . '::Parcelle',
			'flk_cuvage'  => __CLASS__ . '::Cuvage',
			'flk_formulaire_pre_validation'  => __CLASS__ . '::PrevalidationFom',
			'flk_print_votre_profil'  => __CLASS__ . '::Print_Votre_Profil',
			'flk_print_votre_offre'  => __CLASS__ . '::Print_Votre_Offre',
			'flk_print_liste_enfant'  => __CLASS__ . '::Print_Liste_Enfant',
			'flk_carte'  => __CLASS__ . '::Carte',
			'flk_formulaire_validation'  => __CLASS__ . '::ValidationFom',
			'flk_formulaire_recherche'  => __CLASS__ . '::SearchFom',
			'flk_alerte_shortcode'  => __CLASS__ . '::AlerteShortcode',
			// cyril
			'flk_votre_profil_creation'  => __CLASS__ . '::Votre_Profil_Creation',
		);
		foreach ($shortcodes as $shortcode => $function) {
			add_shortcode(apply_filters("{$shortcode}_shortcode_tag", $shortcode), $function);
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function Callback function.
	 * @param array    $atts     Attributes. Default to empty array.
	 * @param array    $wrapper  Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_flk_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'flk',
			'data-group' => "null",
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();

		// @codingStandardsIgnoreStart
		echo empty($wrapper['before']) ? '<div class="' . esc_attr($wrapper['class']) . '" data-group="' . esc_attr($wrapper['data-group']) . '">' : $wrapper['before'];
		call_user_func($function, $atts);
		echo empty($wrapper['after']) ? '</div>' : $wrapper['after'];
		// @codingStandardsIgnoreEnd

		return ob_get_clean();
	}

	/**
	 * Include shortcode file
	 */
	public static function include()
	{
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-hello-world.php';
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-votre-profil.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-votre-offre.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-parcelle.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-cuvage.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-prevalidation-formulaire.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-carte.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-print-votre-profil.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-print-votre-offre.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-print-liste-enfant.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-validation-formulaire.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-search-formulaire.php'; // on include le fichier du shortcode
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-alerte-abonne.php'; // on include le fichier du shortcode
		
		/*cyril*/
		include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-votre-profil-creation.php';
	}


	/**
	 * Hello World shortcodes.
	 */
	public function Hello_World()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Hello_World', 'output'));
	}


	public static function Votre_Profil()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Votre_Profil', 'output'), array(), array(
			'class'  => 'flk votre_profil',
			'data-group' => "votre_profil",
			'before' => null,
			'after'  => null,
		));
	}
	
	// cyril
	public static function Votre_Profil_Creation()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Votre_Profil_Creation', 'output'), array(), array(
			'class'  => 'flk Votre_Profil_Creation',
			'data-group' => "votre_profil_creation",
			'before' => null,
			'after'  => null,
		));
	}

	public static function Votre_Offre()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Votre_Offre', 'output'), array(), array(
			'class'  => 'flk votre_offre',
			'data-group' => "votre_offre",
			'before' => null,
			'after'  => null,
		));
	}

	public static function Parcelle($attr)
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Parcelle', 'output'), $attr, array(
			'class'  => 'flk ajout_parcelle',
			'data-group' => "parcelle",
			'before' => null,
			'after'  => null,
		));
	}

	public static function Cuvage($attr)
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Cuvage', 'output'), $attr, array(
			'class'  => 'flk ajout_cuvage',
			'data-group' => "cuvage",
			'before' => null,
			'after'  => null,
		));
	}

	public static function PrevalidationFom()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_PrevalidationFom', 'output'));
	}

	public static function Carte() {
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Carte', 'output'));
	}

	public static function Print_Liste_Enfant() {
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Print_Liste_Enfant', 'output'), array(), array(
			'class'  => 'flk liste_enfant',
			'data-group' => "cuvage",
			'before' => null,
			'after'  => null,
		));
	}

	public static function Print_Votre_Offre() {
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Print_Votre_Offre', 'output'), array(), array(
			'class'  => 'flk votre_offre',
			'data-group' => "cuvage",
			'before' => null,
			'after'  => null,
		));
	}

	public static function Print_Votre_Profil() {
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Print_Votre_Profil', 'output'), array(), array(
			'class'  => 'flk votre_profil',
			'data-group' => "cuvage",
			'before' => null,
			'after'  => null,
		));
	}

	public static function ValidationFom()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_validationFom', 'output'), array(), array(
			'class'  => 'flk formulaire_validation',
			'data-group' => "cuvage",
			'before' => null,
			'after'  => null,
		));
	}


	public static function AlerteShortcode()
	{
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_Alerte_Abonne', 'output'));
	}

	public static Function SearchFom() {
		return self::shortcode_flk_wrapper(array('FLK_Shortcode_searchFom', 'output'));
	}
	/**
	 * Exemple de function à pour créer un shortcode.
	 * function self::shortcode_flk_wrapper(array(ClassName, output))
	 * array(ClassName, output) va appeler la function output de la class ClassName
	 */
	// public function Shortcode_Exemple(){    
	//     return self::shortcode_flk_wrapper( array( 'FLK_Shortcode_Exemple', 'output' ) );
	// }
}
