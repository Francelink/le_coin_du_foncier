<?php

/**
 * Setup menus in WP admin.
 *
 */

defined('ABSPATH') || exit;

if (class_exists('FLK_Admin_Menus', false)) {
    return new FLK_Admin_Menus();
}

/**
 * FLK_Admin_Menus Class.
 */
class FLK_Admin_Menus
{
    /**
     * Déclaration d'une propriété
     *
     */
    // liste des urls
    // public $pages_array = array(
    //     'flk_admin_menu_list_depot' => array(
    //         'Nom' => 'Liste des dépots',
    //         'Description' => 'Consulter et gérer la liste des dépots',
    //         'Url' => 'flk_admin_menu_list_depot'
    //     ), 
    //     'flk_admin_menu_manage_stock' => array(
    //         'Nom' => 'Gestion des stocks',
    //         'Description' => 'Consulter et géré les stocks',
    //         'Url' => 'flk_admin_menu_manage_stock'
    //     ), 
    // );

    // icon du menu
    public $icon_eligo = '<svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95.03 87.41"><defs><style>.cls-1{fill:#a7aaad;}</style></defs><path class="cls-1" d="M103.63,44.26a58.08,58.08,0,0,1,2.58,18.18c-.36,21.89-18.53,40.62-40.48,41.66-9.43.45-18.17-1.42-26.09-6.68a12.9,12.9,0,0,1-2-1.42,2.6,2.6,0,0,1-.22-3.63,2.21,2.21,0,0,1,3.13-.45c.85.52,1.65,1.11,2.52,1.6,12.9,7.31,26.08,7.86,38.9.38s19.22-19,19.27-33.87c0-5.53-1.11-11.69-4-15.93-.83,2-.32,4-.48,5.83-.14,1.7-.84,2.92-2.72,2.9s-2.7-1.22-2.71-2.92c0-5.94,0-11.88,0-17.73,2.63-.6,4.07.75,5.69,1.45,3.66,1.58,7.27,3.25,10.91,4.87,1.73.77,3.61,1.56,2.78,3.93-.72,2-2.55,1.76-4.24,1.38-.65-.14-1.29-.32-1.94-.48-.35-.28-1.12-.09-1.12-.09S103.47,43.8,103.63,44.26Z" transform="translate(-15.79 -16.75)"/><path class="cls-1" d="M22.91,78.44C21.34,73,20,68.29,19.67,63.29,18.12,42.21,32.44,22.53,53.14,17.91a42.38,42.38,0,0,1,33.47,5.94c1.76,1.14,4.45,2.24,2.71,4.88s-3.73.56-5.52-.57a38.3,38.3,0,0,0-58.56,25.3c-1.67,8.66-.56,17.18,4.67,25V75c0-1.17,0-2.34,0-3.51a2.67,2.67,0,0,1,2.69-3c1.82,0,2.47,1.38,2.47,3.09V90.09c-6-2.67-11.44-5.08-16.89-7.48-1.71-.75-2.94-1.9-2.16-3.84s2.43-2,4.17-1.16C20.8,77.88,21.47,78,22.91,78.44Z" transform="translate(-15.79 -16.75)"/><path class="cls-1" d="M59.38,40.42c6.75-2.71,12.8-3,17-1.77.31.09,1.79.13.64-.58-8.54-4.45-17.28-6.28-26-1A32,32,0,0,0,38.36,50.88,23.24,23.24,0,0,0,36.74,67c9.56-2.6,17.54-7.22,23.7-14.52,4.7-5.55,9.91-10.08,17.14-12a5.21,5.21,0,0,0-1-.71C72.51,37.55,59.38,40.42,59.38,40.42Z" transform="translate(-15.79 -16.75)"/><path class="cls-1" d="M91.13,62.94c-.1-1.84.1-3.84-1.93-4.76-2.71-1.23-5.51-.84-8.28-.07-.07,0-.1.22-.15.34s1.23-.13,1.37.54C75.65,62,68.43,65.31,64,71.51c.84.31,2.33-.65,3.2-.33-2.71,2.61-6.6,5.55-10.05,6.75a45.58,45.58,0,0,1-9.74,1.95c-1,.11-1.64.36-.76,1.44,4.18-1,6.2-.21,9.28.75-4.42-.66-6.16-.91-9-.14,1.42,2.59,4,3.43,6.55,4,7,1.53,13.82,1.31,20-1.43.86-.38-.53,1.58.31,1.1,1.27-.72,4.75-3.94,6-4.9a55.65,55.65,0,0,0,6.82-6.57c.68-.83.87-2,1.36-4,.06-.25.31-.14.3,0a5.74,5.74,0,0,1-.77,2.91,9.91,9.91,0,0,1,.79-.8C90.75,69.74,91.34,66.63,91.13,62.94Z" transform="translate(-15.79 -16.75)"/><path class="cls-1" d="M76.34,38.65l.59-.11,0-.47a4.7,4.7,0,0,1,.59.48,2.73,2.73,0,0,1,.46.74,7,7,0,0,0-.65-.28C77,38.85,76.34,38.65,76.34,38.65Z" transform="translate(-15.79 -16.75)"/></svg>';


    /**
     * Hook in tabs.
     */
    public function __construct()
    {
        // Add menus
        add_action('admin_menu', array($this, 'admin_flk_menu'), 10);
    }

    public function admin_flk_menu()
    {
        global $menu;
        // Menu principale
        add_menu_page("FLK", "FLK", 'manage_options', 'flk_admin', array($this, 'RenderMenuFLK'), '', '56.5');
        // Sous menu 
        add_submenu_page('flk_admin', 'Debugg FLK', 'Debugg FLK', 'manage_options', 'flk_admin_debugg', array($this, 'RenderDebugg'));
    }

    public function RenderMenuFLK()
    {
        $data = array();
        FLK_Render_template::renderTemplate('admin', $data);
    }

    public function RenderDebugg()
    {
        echo 'Debugg';
        //
    }

    // public function RenderListeEntrepot()
    // {
    //     // liste des produits
    //     $usines = FLK_Bdd_request::flk_query('select * from table_flk_new_usine');
    //     $points_relais = FLK_Bdd_request::flk_query('select * from table_flk_new_point_relais');
    //     $data = array(
    //         'id' => $post->ID,
    //         'pointsRelais' => $points_relais,
    //         'usines' => $usines
    //     );
    //     FLK_Render_template::renderTemplate('gestion-des-stocks', $data);
    // }
}
