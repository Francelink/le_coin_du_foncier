<?php

/**
 * Plugin Name: FLK Custom
 * Description: Plugin développé par Francelink
 * Plugin URI: https://francelink.net/
 * Author: Francelink
 * Version: 1
 * Elementor tested up to: 3.4.0
 * Author URI: https://francelink.net/
 *
 * Text Domain: elementor-pro
 */

defined('ABSPATH') || exit;

/**
 * Definir les variables d'environement
*/
if (!defined('FLK_PLUGIN_DIR')) {
    define('FLK_PLUGIN_DIR', __DIR__);
}
if (!defined('FLK_PLUGIN_DIR_URL')) {
    define('FLK_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
}

// Include the main FLK Custom class.
if (!class_exists('FLK_Custom', false)) {
    include_once FLK_PLUGIN_DIR . '/includes/class-flk-custom.php';
}

/**
 * Returns the main instance.
 *
 */
function FLK()
{ // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
    return FLK_Custom::instance();
}

// Global for backwards compatibility.
$GLOBALS['flk'] = FLK();


add_filter( 'posts_results', 'wpse46014_peek_into_private', null, 2 );
function wpse46014_peek_into_private( $posts, $query ) {

    if ( sizeof( $posts ) != 1 ) return $posts; /* not interested */

    $status = get_post_status( $posts[0] );
    $post_status_obj = get_post_status_object( $status );

    if ( $post_status_obj->public ) return $posts; /* it's public */

    if ( !isset( $_GET['key'] ) || $_GET['key'] != 'foryoureyesonly' )
        return $posts; /* not for your eyes */

    $query->_my_private_stash = $posts; /* stash away */

    add_filter( 'the_posts', 'wpse46014_inject_private', null, 2 );
}

function wpse46014_inject_private( $posts, $query ) {
    /* do only once */
    remove_filter( 'the_posts', 'wpse46014_inject_private', null, 2 );
    return $query->_my_private_stash;
}