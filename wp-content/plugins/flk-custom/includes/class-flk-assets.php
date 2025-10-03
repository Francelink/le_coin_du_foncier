<?php

/**
 * Handle frontend scripts
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend scripts class.
 */
class FLK_Assets
{

    /**
     * Contains an array of script handles registered.
     *
     * @var array
     */
    private static $styles = array();

    /**
     * FLK plugin version.
     *
     */
    private static $version = FLK_PLUGIN_VERSION;

    /**
     * Hook in methods.
     */
    public static function init()
    {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'load_scripts_frontend'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'load_scripts_backend'), 999);
    }

    /**
     * Register/queue frontend style and scripts.
     */
    public static function load_scripts_frontend()
    {
        // JS Styles.
        $register_scripts = self::get_scripts_frontend();
        if ($register_scripts) {
            foreach ($register_scripts as $handle => $args) {
                // Disable files for debugg 
                if ($args['disable'] === true) {
                    return;
                }
                if (!isset($args['has_rtl'])) {
                    $args['has_rtl'] = false;
                }
                // WP function Enqueue a JS stylesheet.
                // wp_enqueue_script( string $handle, string $src = '', string[] $deps = array(), string|bool|null $ver = false, bool $in_footer = false ); 
                wp_enqueue_script($handle, $args["src"], $args["deps"], $args["version"], $args["in_footer"]);
                // in JavaScript, object properties are accessed as ajax_object.ajax_url
                wp_localize_script($handle, 'flk_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
            }
        }

        // CSS Styles.
        $enqueue_styles = self::get_styles_frontend();
        if ($enqueue_styles) {
            foreach ($enqueue_styles as $handle => $args) {
                // Disable files for debugg 
                if ($args['disable'] === true) {
                    return;
                }
                if (!isset($args['has_rtl'])) {
                    $args['has_rtl'] = false;
                }
                // WP function Enqueue a CSS stylesheet.
                // wp_enqueue_style( string $handle, string $src = '', string[] $deps = array(), string|bool|null $ver = false, string $media = 'all' )
                wp_enqueue_style($handle, $args["src"], $args["deps"], $args["version"], $args["media"]);
            }
        }
    }


    /**
     * Get styles for the frontend.
     * TODO: Add style file in flk_enqueue_styles_frontend array
     * @return array
     */
    public static function get_styles_frontend()
    {
        return apply_filters(
            'flk_enqueue_styles_frontend',
            array(
                'flk-custom-general-style'      => array(
                    'src'     => FLK_PLUGIN_DIR_URL . '/assets/css/flk-custom-general-style.css',
                    'deps'    => '',
                    'version' => self::$version,
                    'media'   => 'all',
                    'has_rtl' => true,
                    'disable' => false,
                ),
                'leaflet-style'      => array(
                    'src'     => FLK_PLUGIN_DIR_URL . '/assets/js/external/leaflet/leaflet.css',
                    'deps'    => '',
                    'version' => self::$version,
                    'media'   => 'all',
                    'has_rtl' => true,
                    'disable' => false,
                ),
                //\\mv08.francelink.net\d$\WWWRoot\Coindufoncier\wp-content\plugins\flk-custom\assets\css\flk-custom-general-style.css
                // 'woocommerce-smallscreen' => array(
                //     'src'     => self::get_asset_url('assets/css/woocommerce-smallscreen.css'),
                //     'deps'    => 'woocommerce-layout',
                //     'version' => $version,
                //     'media'   => 'only screen and (max-width: ' . apply_filters('woocommerce_style_smallscreen_breakpoint', '768px') . ')',
                //     'has_rtl' => true,
                //     'disable' => false,
                // ),
            )
        );
    }

    /**
     * Get script for the frontend.
     * TODO: Add script file in flk_enqueue_script_frontend array
     * @return array
     */
    public static function get_scripts_frontend()
    {
        return apply_filters(
            'flk_enqueue_script_frontend',
            array(
                'flk-custom-general-script'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/assets/js/flk-custom-general-script.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                'turf-script'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/assets/js/external/turf.min.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                'leaflet-script'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/assets/js/external/leaflet/leaflet.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                'jquery-mask-script'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/assets/js/external/jquery-mask-plugin/dist/jquery.mask.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                'leaflet-chineseTmsProviders'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/assets/js/external/leaflet-chineseTmsProviders/src/leaflet.ChineseTmsProviders.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                // 'woocommerce-smallscreen' => array(
                //     'src'     => self::get_asset_url('assets/css/woocommerce-smallscreen.css'),
                //     'deps'    => 'woocommerce-layout',
                //     'version' => $version,
                //     'media'   => 'only screen and (max-width: ' . apply_filters('woocommerce_style_smallscreen_breakpoint', '768px') . ')',
                //     'has_rtl' => true,
                //     'disable' => false,
                // ),
            )
        );
    }

    /**
     * Register/queue frontend backend.
     */
    public static function load_scripts_backend()
    {
        // JS Styles.
        $register_scripts = self::get_scripts_backend();
        if ($register_scripts) {
            foreach ($register_scripts as $handle => $args) {
                // Disable files for debugg 
                if ($args['disable'] === true) {
                    return;
                }
                if (!isset($args['has_rtl'])) {
                    $args['has_rtl'] = false;
                }
                // WP function Enqueue a JS stylesheet.
                // wp_enqueue_script( string $handle, string $src = '', string[] $deps = array(), string|bool|null $ver = false, bool $in_footer = false )
                wp_enqueue_script($handle, $args["src"], $args["deps"], $args["version"], $args["in_footer"]);
            }
        }

        // CSS Styles.
        $enqueue_styles = self::get_styles_backend();
        if ($enqueue_styles) {
            foreach ($enqueue_styles as $handle => $args) {
                // Disable files for debugg 
                if ($args['disable'] === true) {
                    return;
                }
                if (!isset($args['has_rtl'])) {
                    $args['has_rtl'] = false;
                }
                // WP function Enqueue a CSS stylesheet.
                // wp_enqueue_style( string $handle, string $src = '', string[] $deps = array(), string|bool|null $ver = false, string $media = 'all' )
                wp_enqueue_style($handle, $args["src"], $args["deps"], $args["version"], $args["media"]);
            }
        }
    }


    /**
     * Get styles for the backend.
     * TODO : Add style file in flk_enqueue_styles_frontend array
     * TODO : To disable a style file in flk_enqueue_styles_frontend array pass 'disable' => true,
     * @return array
     */
    public static function get_styles_backend()
    {
        return apply_filters(
            'flk_enqueue_styles_frontend',
            array(
                'flk-custom-general-style'      => array(
                    'src'     => FLK_PLUGIN_DIR_URL . '/admin/assets/css/flk-custom-admin-style.css',
                    'deps'    => '',
                    'version' => self::$version,
                    'media'   => 'all',
                    'has_rtl' => true,
                    'disable' => false,
                ),
                // 'woocommerce-smallscreen' => array(
                //     'src'     => self::get_asset_url('assets/css/woocommerce-smallscreen.css'),
                //     'deps'    => 'woocommerce-layout',
                //     'version' => $version,
                //     'media'   => 'only screen and (max-width: ' . apply_filters('woocommerce_style_smallscreen_breakpoint', '768px') . ')',
                //     'has_rtl' => true,
                //     'disable' => false,
                // ),
            )
        );
    }

    /**
     * Get script for the backend.
     * TODO: Add script file in flk_enqueue_script_backend array
     * TODO: To disable a style file in flk_enqueue_styles_frontend array pass 'disable' => true,
     * @return array
     */
    public static function get_scripts_backend()
    {
        return apply_filters(


            'flk_enqueue_script_backend',
            array(
                'flk-custom-general-script'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/admin/assets/js/flk-custom-admin-script.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                'flk-custom-function'      => array(
                    'src'       => FLK_PLUGIN_DIR_URL . '/admin/assets/js/flk-custom-function.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                'turf'      => array(
                    'src'       => 'https://unpkg.com/@turf/turf@6/turf.min.js',
                    'deps'      => '',
                    'version'   => self::$version,
                    'in_footer' => true,
                    'has_rtl'   => true,
                    'disable' => false,
                ),
                // 'woocommerce-smallscreen' => array(
                //     'src'     => self::get_asset_url('assets/css/woocommerce-smallscreen.css'),
                //     'deps'    => 'woocommerce-layout',
                //     'version' => $version,
                //     'media'   => 'only screen and (max-width: ' . apply_filters('woocommerce_style_smallscreen_breakpoint', '768px') . ')',
                //     'has_rtl' => true,
                //     'disable' => false,
                // ),
            )
        );
    }
}

FLK_Assets::init();
