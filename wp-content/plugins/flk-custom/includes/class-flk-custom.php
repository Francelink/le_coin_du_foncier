<?php

/**
 * FLK Custom setup
 */

defined('ABSPATH') || exit;
/**
 * Main FLK Custom Class.
 *
 * @class FLK_Custom 
 */
final class FLK_Custom
{
    /**
     * The single instance of the class.
     *
     */
    protected static $_instance = null;
    /**
     * Main FLK Instance.
     *
     * 
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct()
    {
        // var_dump('ok');die;
        $this->includes();
        $this->define_constants();
        $this->init_hooks();
    }

    /**
     * Define FLK Constants.
     */
    private function define_constants()
    {
        // if (!defined('VARIABLE')) {
        //     define('VARIABLE', valeur);
        // }
        if (!defined('FLK_PLUGIN_VERSION')) {
            define('FLK_PLUGIN_VERSION', '1.0');
        }
        if (!defined('FLK_PLUGIN_TXT_DIR_URL')) {
            define('FLK_PLUGIN_TXT_DIR_URL', __DIR__ . '/assets/txt');
        }
        if (!defined('FLK_PLUGIN_FILE_FORMAT')) {
            define('FLK_PLUGIN_FILE_FORMAT', '.txt');
        }
        if (!defined('FLK_TEMPLATE_DIR')) {
            define('FLK_TEMPLATE_DIR', FLK_PLUGIN_DIR . '/templates');
        }
        if (!defined('FLK_PLUGIN_LOG_DIR')) {
            define('FLK_PLUGIN_LOG_DIR', FLK_PLUGIN_DIR . '/logs');
        }
    }

    /**
     * on défini les class à appeler 
    */
    public function includes()
    {
        // ---- Class Admin ---- //
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/admin/class-flk-admin.php';
        // ---- Class ---- //
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-user.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-bdd-request.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-error.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-render-template.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-objet-formatter.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-ajax-api-controller.php' ;
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-bdd-request.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-class-action-obtenir-les-infos.php';
        /// ---- Class Assets ---- //
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-assets.php';
        /// ---- Class Hook ---- //
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-hooks.php';
        include_once WP_PLUGIN_DIR  . '/flk-custom/includes/class-flk-shortcodes.php';
    }


    /**
     * Hook into actions and filters.
     */
    private function init_hooks()
    {
        // var_dump('ok');die;
        add_action( 'init', array( 'FLK_Shortcodes', 'init' ) );
    }
}
