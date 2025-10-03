<?php

/**
 * Setup Class
 * read : https://www.php.net/manual/fr/language.oop5.php
 * A copier coller pour la création d'une classe
 *
 */
defined('ABSPATH') || exit;

/**
 * On déclare la class si elle est existe
 *
 */
if (class_exists('FLK_Ajax_Api_Controller', false)) {
    return new FLK_Ajax_Api_Controller();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_Ajax_Api_Controller {
    /**
     * Déclaration d'une propriété
     *
     */

    //
    public $var = 'une valeur par défaut';

    /**
     * Constructeurs
     *
     */
    public function __construct() {
        add_action('wp_ajax_printShorcode', array($this, 'printShorcode')); // action pour ajouter un route pour les appels AJAX via wordpress action
        add_action('wp_ajax_nopriv_printShorcode', array($this, 'printShorcode')); // action pour ajouter un route pour les appels AJAX via wordpress action
        add_action('wp_ajax_createPost', array($this, 'createPost')); // action pour ajouter un route pour les appels AJAX via wordpress action
        add_action('wp_ajax_nopriv_createPost', array($this, 'createPost')); // action pour ajouter un route pour les appels AJAX via wordpress action
        add_action('rest_api_init', function () {
            // Route pour la REST API url /wp-json/flk_api/v1/ + uri passé en deuxièmre argument
            register_rest_route('flk_api/v1', '/createPost', array(
                'methods'  => 'POST',
                'callback' => array($this, 'createPost'),
                // 'args'     => array(
                //     'type_offre' => array(
                //         'description'       => 'Type de l\'offre parcelle ou cuvage',
                //         'type'              => 'string',
                //     ),
                //     'inputs_parent' => array(
                //         'description'       => 'Liste des champs de l\'offre',
                //         'type'              => 'array',
                //     ),
                //     'inputs_enfant' => array(
                //         'description'       => 'Liste des champs du/des pacelle(s) ou du cuvage',
                //         'type'              => 'array',
                //     ),
                // )
            ));
        });
        add_action('rest_api_init', function () {
            // Route pour la REST API url /wp-json/flk_api/v1/ + uri passé en deuxièmre argument
            register_rest_route('flk_api/v1', '/publishPost', array(
                'methods'  => 'POST',
                'callback' => array($this, 'publishPost'),
                // 'args'     => array(
                //     'parent' => array(
                //         'description'       => 'ID de l\'offre à publier ',
                //         'type'              => 'string',
                //     ),
                //     'enfants' => array(
                //         'description'       => 'Liste des id du/des parcelle(s) ou du cuvage à publier',
                //         'type'              => 'array',
                //     ),
                // )
            ));
        });
        add_action('rest_api_init', function () {
            // Route pour la REST API url /wp-json/flk_api/v1/ + uri passé en deuxièmre argument
            register_rest_route('flk_api/v1', '/searchPost', array(
                'methods'  => 'POST',
                'callback' => array($this, 'searchPost'),
            ));
        });
        add_action('rest_api_init', function () {
            // Route pour la REST API url /wp-json/flk_api/v1/ + uri passé en deuxièmre argument
            register_rest_route('flk_api/v1', '/reinitPost', array(
                'methods'  => 'POST',
                'callback' => array($this, 'reinitPost'),
            ));
        });
        add_action('rest_api_init', function () {
            // Route pour la REST API url /wp-json/flk_api/v1/ + uri passé en deuxièmre argument
            register_rest_route('flk_api/v1', '/AlerteInsert', array(
                'methods'  => 'POST',
                'callback' => array($this, 'AlerteInsert'),
                // 'args'     => array(
                //     'commune' => array(
                //         'description'       => 'Le commune de l\'alerte',
                //         'type'              => 'string',
                //     ),
                //     'mail' => array(
                //         'description'       => 'Email de l\'abonne',
                //         'type'              => 'string',
                //     ),
                // )
            ));
        });
    }

    /**
     * Il prend un objet `$ post`, extrait la `commune` et `email` du corps JSON et les enregistre dans la
     * base de données
     *
     * @param post L'objet poste.
     */
    public static function AlerteInsert($post) {
        // on récupère les données en $_POST
        $json = $post->get_json_params();
        // On récupère la commune et l'email
        $commune = $json["commune"];
        $email   = $json["email"];
        // et on l'enregistre en BDD
        $insert = FLK_Bdd_request::flk_insert('flk_alerte_abonne', array("villes" => $commune, "email" => $email));
        if ($insert === 1) {
            $data           = new stdClass();
            $data->response = 'ok';
            return new WP_REST_Response($data, 200);
        }
    }

    /**
     * Renvoie le HTML du Shortcode
     *
     */
    public static function printShorcode() {
        if (isset($_POST['shorcode'])) {
            if ($_POST['id'] === "false") {
                echo do_shortcode('[' . $_POST["shorcode"] . ']');
            } else {
                echo do_shortcode('[' . $_POST["shorcode"] . ' id="' . $_POST['id'] . '" ]');
            }
        }
        //die();
    }

    /**
     * Il prend un objet JSON, crée une publication et renvoie l'ID de la publication
     *
     * @param post L'objet WP_REST_Request.
     */
    public static function createPost($post) {
        // on récupère les données en $_POST
        $json          = $post->get_json_params();
        $type_offre    = (int) $json["type_offre"];
        $inputs_parent = $json["inputs_parent"];
        $inputs_enfant = $json["inputs_enfant"];
        //console.log( $inputs_parent);

        // status publish od draft
        // On prépare les éléments pour la création de l'article Offre
        $post_parent = array(
            'post_title'    => "Offre ",
            //'post_title'    => $inputs_parent,
            'post_status'   => 'draft',
            'post_author'   => 4,
            'post_category' => array(10, $type_offre),
        );
        // On insert les élements en BDD
        $insert_id_parent_offre = wp_insert_post($post_parent);
        // On modifie le titre avec l'id du post crée
        $post_update = array(
            'ID'         => $insert_id_parent_offre,
            'post_title' => "Offre n° " . $insert_id_parent_offre,
        );
        wp_update_post($post_update);
        // On crée les champs ACF de l'offre
        self::insertACFInput($inputs_parent, $insert_id_parent_offre);
        // on met à jour la meta template de l'article crée
        update_post_meta($insert_id_parent_offre, "_wp_page_template", "");
        // on crée un array qui sera renvoyé avec les infos des enfants
        $data_enfant = array(
            "categorie"         => $type_offre,
            "liste_des_enfants" => array(),
        );
        // On vérifie le type d'offre pour crée le/les parcelle.s ou cuvage.s associé.s
        if ($type_offre === 11) { // on ajoute une ou des parcelle.s
            foreach ($inputs_enfant as $key => $parcelle) {
                $post_enfant_title    = "Parcelle numéro " . $parcelle["ajouter_une_parcelle"][0]["field_62eb9f430fb79"]["value"];
                $post_enfant_category = array(14);
                // On prépare les éléments pour la création de l'article Parcelle
                $post_enfant = array(
                    'post_title'    => $post_enfant_title,
                    'post_status'   => 'draft',
                    'post_author'   => 4,
                    'post_category' => $post_enfant_category,
                );
                // On insert les élements en BDD
                $insert_id_enfant_offre = wp_insert_post($post_enfant);
                // On crée les champs ACF
                self::insertACFParentInput($insert_id_parent_offre, $insert_id_enfant_offre);
                self::insertACFInput($parcelle, $insert_id_enfant_offre);
                /// on push les élements de l'enfant
                array_push($data_enfant["liste_des_enfants"], $insert_id_enfant_offre);
            }
        } else { // on ajoute un ou des cuvage.s
            foreach ($inputs_enfant as $key => $cuvage) {
                $post_enfant_title    = "Cuvage de " . $inputs_enfant[0]["lieu"][0]["field_62ebbd42e30e0"]["value"];
                $post_enfant_category = array(13);
                // On prépare les éléments pour la création de l'article Cuvage
                $post_enfant = array(
                    'post_title'    => $post_enfant_title,
                    'post_status'   => 'draft',
                    'post_author'   => 4,
                    'post_category' => $post_enfant_category,
                );
                // On insert les élements en BDD
                $insert_id_enfant_offre = wp_insert_post($post_enfant);
                // On crée les champs ACF
                self::insertACFParentInput($insert_id_parent_offre, $insert_id_enfant_offre);
                self::insertACFInput($cuvage, $insert_id_enfant_offre);
                array_push($data_enfant["liste_des_enfants"], $insert_id_enfant_offre);
            }
        }

        // on prépare l'objet à renvoyer
        $data                  = new stdClass();
        $data->id_parent_offre = $insert_id_parent_offre;
        $data->enfant          = $data_enfant;
        // echo json_encode($data);
        // on crée un cookie avec les infos de l'enfant et du parent
        if (!isset($_SESSION['data_flk_offre_parent_enfant'])) {
            $_SESSION['data_flk_offre_parent_enfant'] = json_encode($data);
        } else {
            $_SESSION['data_flk_offre_parent_enfant'] = json_encode($data);
        }
        // on renvoie une réponse
        return new WP_REST_Response($data, 200);
        // exit;
    }

    /**
     * Création des champs ACF pour les articles
     *
     */
    public static function insertACFInput($inputs, $post_id) {
        if ($inputs) { // Si l'objet contenant les gourpe de champs existe
            foreach ($inputs as $k => $groupe_de_champs) {
                if ( isset($groupe_de_champs[0]) ) {
                    foreach ($groupe_de_champs[0] as $key => $input) {
                        // on met le champ à jour
                        // todo logs
                        if ($input["type"] === "select") {
                            update_field($k . '_' . $input["name"], $input["value"], $post_id);
                        } else {
                            $update = update_field($k . '_' . $input["name"], $input["value"], $post_id);
                        }
                    }
                }

                // on ajoute les conditions 
                if ( isset($groupe_de_champs["conditions"]) ) {
                    foreach ($groupe_de_champs as $key => $input) {
                        // on met le champ à jour
                        // todo logs
                        if ($input["type"] === "select") {
                            update_field('conditions_' . $input["name"], $input["value"], $post_id);
                        } else {
                            $update = update_field('conditions_' . $input["name"], $input["value"], $post_id);
                        }
                    }
                }

                // if (have_rows($k, $post_id)) { // Si le groupe de champ existe on parcourt les champs du group
                //     foreach ($groupe_de_champs[0] as $key => $input) {
                //         // on met le champ à jour
                //         // todo logs
                //         var_dump($inputs, $k, $input["name"], $input["value"], $groupe_de_champs[0]);
                //         $update = update_field($k . '_' . $input["name"], $input["value"], $post_id);
                //     }
                // };
            }
        }
    }

    /**
     * Création du champs "parent" => lien entre offre et parcelle.s ou cuvage.s
     *
     */
    public static function insertACFParentInput($id, $id_enfant) {
        if ($id) {
            update_field('parent', $id, $id_enfant);
        }
    }

    /**
     * Publication de l'offre et du/des parcelle.s ou cuvage.s associé.s
     *
     */
    public static function publishPost($post) {
        // on récupère les données en $_POST
        $json = $post->get_json_params();
        // on publie l'offre
        $parent_post_status = get_post_status($json["parent"]);
        //TODO Check pourquoi ça ne le passe pas en "publié"
        if ($parent_post_status === "draft") { // si l'article est en brouillon on le publie
            $my_post = array(
                'ID'          => $json["parent"],
                'post_status' => 'publish',
            );
            //todo logs
            $return_parent_publish = wp_update_post($my_post);


            // on mets à jours les cases à cocher
            $cases = $json["cases"];
            self::insertACFInput($cases, $json["parent"]);
        }
        // on publie les articles enfants
        // on crée un tableau vide pour accuellir les communes des enfants
        $liste_des_communes = array();
        foreach ($json["enfants"] as $key => $value) {
            $enfant_post_status = get_post_status($value);
            if ($enfant_post_status === "draft") { // si l'article est en brouillon on le publie
                $my_post = array(
                    'ID'          => $value,
                    'post_status' => 'publish',
                );
                //todo logs
                $category = get_the_category($value);
                // on récupère la commune de l'enfant et on la push dans le tableau liste des communes
                if (count($category) >= 1) {
                    $category_id = $category[0]->term_id;
                    if ($category_id === 13) {
                        $commune = get_field("lieu_code_insee", $value);
                        array_push($liste_des_communes, $commune);
                    } else {
                        $commune = get_field("ajouter_une_parcelle_code_insee", $value);
                        array_push($liste_des_communes, $commune);
                    }
                }
                $return_enfant_publish = wp_update_post($my_post);
            }
        }

        // on supprime les doublons
        $liste_des_communes = array_unique($liste_des_communes);
        // on fait un foreach dans le nouveau tableau
        foreach ($liste_des_communes as $key => $commune) {
            // pour chaque on fait un recherche un BDD pour avoir les abonnées et on envoie le mail
            // On fait une recherche, on récupère les abonnées et on vérifier s'ils sont abonnées à la ville
            $emails = FLK_Bdd_request::flk_query("SELECT email FROM flk_alerte_abonne WHERE villes='" . $commune . "'");
            // on récupère le nom de la commune
            $field       = get_field_object('lieu_code_insee', 1261);
            $nom_commune = $field['choices'][$commune];

            foreach ($emails as $key => $email) {
                // Pour envoyer le mail c'est dans wp_mail();
                $subject = '1 nouvelle offre lecoindufoncier pour' . $nom_commune;
                // on va mettre un titre
                $text = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:o="urn:schemas-microsoft-com:office:office">
                <head>
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="format-detection" content="telephone=no">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title></title>
                    <style type="text/css" emogrify="no">
                        #outlook a {
                            padding: 0;
                        }

                        .ExternalClass {
                            width: 100%;
                        }

                        .ExternalClass,
                        .ExternalClass p,
                        .ExternalClass span,
                        .ExternalClass font,
                        .ExternalClass td,
                        .ExternalClass div {
                            line-height: 100%;
                        }

                        table td {
                            border-collapse: collapse;
                            mso-line-height-rule: exactly;
                        }

                        .editable.image {
                            font-size: 0 !important;
                            line-height: 0 !important;
                        }

                        .nl2go_preheader {
                            display: none !important;
                            mso-hide: all !important;
                            mso-line-height-rule: exactly;
                            visibility: hidden !important;
                            line-height: 0px !important;
                            font-size: 0px !important;
                        }

                        body {
                            width: 100% !important;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                            margin: 0;
                            padding: 0;
                        }

                        img {
                            outline: none;
                            text-decoration: none;
                            -ms-interpolation-mode: bicubic;
                        }

                        a img {
                            border: none;
                        }

                        table {
                            border-collapse: collapse;
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                        }

                        th {
                            font-weight: normal;
                            text-align: left;
                        }

                        *[class="gmail-fix"] {
                            display: none !important;
                        }
                    </style>
                    <style type="text/css" emogrify="no">
                        @media (max-width: 600px) {
                            .gmx-killpill {
                                content: "\03D1";
                            }
                        }
                    </style>
                    <style type="text/css" emogrify="no">
                        @media (max-width: 600px) {
                            .gmx-killpill {
                                content: "\03D1";
                            }

                            .r0-c {
                                box-sizing: border-box !important;
                                width: 100% !important
                            }

                            .r1-o {
                                border-style: solid !important;
                                width: 100% !important
                            }

                            .r2-i {
                                background-color: #ffffff !important
                            }

                            .r3-c {
                                box-sizing: border-box !important;
                                text-align: center !important;
                                valign: top !important;
                                width: 320px !important
                            }

                            .r4-o {
                                border-style: solid !important;
                                margin: 0 auto 0 auto !important;
                                width: 320px !important
                            }

                            .r5-i {
                                padding-bottom: 20px !important;
                                padding-left: 15px !important;
                                padding-right: 15px !important;
                                padding-top: 20px !important
                            }

                            .r6-c {
                                box-sizing: border-box !important;
                                display: block !important;
                                valign: top !important;
                                width: 100% !important
                            }

                            .r7-i {
                                padding-left: 0px !important;
                                padding-right: 0px !important
                            }

                            .r8-c {
                                box-sizing: border-box !important;
                                text-align: center !important;
                                valign: top !important;
                                width: 100% !important
                            }

                            .r9-o {
                                background-size: auto !important;
                                border-style: solid !important;
                                margin: 0 auto 0 auto !important;
                                width: 100% !important
                            }

                            .r10-o {
                                border-style: solid !important;
                                margin: 0 auto 0 auto !important;
                                width: 100% !important
                            }

                            .r11-i {
                                background-color: #ffffff !important;
                                padding-bottom: 20px !important;
                                padding-left: 15px !important;
                                padding-right: 15px !important;
                                padding-top: 0px !important
                            }

                            .r12-c {
                                box-sizing: border-box !important;
                                text-align: center !important;
                                width: 100% !important
                            }

                            .r13-i {
                                background-color: #e5f1cf !important;
                                padding-bottom: 0px !important;
                                padding-top: 0px !important
                            }

                            .r14-c {
                                box-sizing: border-box !important;
                                text-align: left !important;
                                valign: top !important;
                                width: 100% !important
                            }

                            .r15-o {
                                border-style: solid !important;
                                margin: 0 auto 0 0 !important;
                                width: 100% !important
                            }

                            .r16-i {
                                padding-bottom: 15px !important;
                                padding-top: 15px !important;
                                text-align: left !important
                            }

                            .r17-c {
                                box-sizing: border-box !important;
                                text-align: center !important;
                                valign: middle !important;
                                width: 100% !important
                            }

                            .r18-o {
                                background-size: cover !important;
                                border-style: solid !important;
                                margin: 0 auto 0 auto !important;
                                width: 100% !important
                            }

                            .r19-i {
                                background-color: #ffffff !important;
                                padding-bottom: 20px !important;
                                padding-left: 15px !important;
                                padding-right: 15px !important;
                                padding-top: 20px !important
                            }

                            .r20-c {
                                box-sizing: border-box !important;
                                display: block !important;
                                valign: middle !important;
                                width: 100% !important
                            }

                            .r21-i {
                                padding-bottom: 0px !important;
                                padding-top: 15px !important
                            }

                            .r22-i {
                                background-color: #8eae58 !important;
                                padding-bottom: 10px !important;
                                padding-left: 15px !important;
                                padding-right: 15px !important;
                                padding-top: 0px !important
                            }

                            .r23-i {
                                padding-bottom: 10px !important;
                                padding-top: 15px !important;
                                text-align: center !important
                            }

                            .r24-i {
                                padding-bottom: 0px !important;
                                padding-top: 0px !important;
                                text-align: center !important
                            }

                            body {
                                -webkit-text-size-adjust: none
                            }

                            .nl2go-responsive-hide {
                                display: none
                            }

                            .nl2go-body-table {
                                min-width: unset !important
                            }

                            .mobshow {
                                height: auto !important;
                                overflow: visible !important;
                                max-height: unset !important;
                                visibility: visible !important;
                                border: none !important
                            }

                            .resp-table {
                                display: inline-table !important
                            }

                            .magic-resp {
                                display: table-cell !important
                            }
                        }
                    </style>
                    <!--[if !mso]><!-->
                    <style type="text/css" emogrify="no">
                        @import url("https://fonts.googleapis.com/css2?family=Montserrat");
                    </style>
                    <!--<![endif]-->
                    <style type="text/css">
                        p,
                        h1,
                        h2,
                        h3,
                        h4,
                        ol,
                        ul {
                            margin: 0;
                        }

                        a,
                        a:link {
                            color: #0092ff;
                            text-decoration: underline
                        }

                        .nl2go-default-textstyle {
                            color: #3b3f44;
                            font-family: arial, helvetica, sans-serif;
                            font-size: 16px;
                            line-height: 1.5
                        }

                        .default-button {
                            border-radius: 4px;
                            color: #ffffff;
                            font-family: arial, helvetica, sans-serif;
                            font-size: 16px;
                            font-style: normal;
                            font-weight: normal;
                            line-height: 1.15;
                            text-decoration: none;
                            width: 50%
                        }

                        .default-heading1 {
                            color: #1F2D3D;
                            font-family: arial, helvetica, sans-serif;
                            font-size: 36px
                        }

                        .default-heading2 {
                            color: #1F2D3D;
                            font-family: arial, helvetica, sans-serif;
                            font-size: 32px
                        }

                        .default-heading3 {
                            color: #1F2D3D;
                            font-family: arial, helvetica, sans-serif;
                            font-size: 24px
                        }

                        .default-heading4 {
                            color: #1F2D3D;
                            font-family: arial, helvetica, sans-serif;
                            font-size: 18px
                        }

                        a[x-apple-data-detectors] {
                            color: inherit !important;
                            text-decoration: inherit !important;
                            font-size: inherit !important;
                            font-family: inherit !important;
                            font-weight: inherit !important;
                            line-height: inherit !important;
                        }

                        .no-show-for-you {
                            border: none;
                            display: none;
                            float: none;
                            font-size: 0;
                            height: 0;
                            line-height: 0;
                            max-height: 0;
                            mso-hide: all;
                            overflow: hidden;
                            table-layout: fixed;
                            visibility: hidden;
                            width: 0;
                        }
                    </style>
                    <!--[if mso]><xml> <o:OfficeDocumentSettings> <o:AllowPNG/> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><![endif]-->
                    <style type="text/css">
                        a:link {
                            color: #0092ff;
                            text-decoration: underline;
                        }
                    </style>
                </head>

                <body text="#3b3f44" link="#0092ff" yahoo="fix" style="">
                    <table cellspacing="0" cellpadding="0" border="0" role="presentation" class="nl2go-body-table" width="100%"
                        style="width: 100%;">
                        <tr>
                            <td align="" class="r0-c">
                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="100%" class="r1-o"
                                    style="table-layout: fixed; width: 100%;">
                                    <!-- -->
                                    <tr>
                                        <td valign="top" class="r2-i" style="background-color: #ffffff;">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                <tr>
                                                    <td class="r3-c" align="center">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation"
                                                            width="600" class="r4-o" style="table-layout: fixed; width: 600px;">
                                                            <!-- -->
                                                            <tr class="nl2go-responsive-hide">
                                                                <td height="20" style="font-size: 20px; line-height: 20px;">­</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="r5-i">
                                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                                                        role="presentation">
                                                                        <tr>
                                                                            <th width="100%" valign="top" class="r6-c"
                                                                                style="font-weight: normal;">
                                                                                <table cellspacing="0" cellpadding="0" border="0"
                                                                                    role="presentation" width="100%" class="r1-o"
                                                                                    style="table-layout: fixed; width: 100%;">
                                                                                    <!-- -->
                                                                                    <tr>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                        <td valign="top" class="r7-i">
                                                                                            <table width="100%" cellspacing="0"
                                                                                                cellpadding="0" border="0"
                                                                                                role="presentation">
                                                                                                <tr>
                                                                                                    <td class="r8-c" align="center">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="342" class="r9-o"
                                                                                                            style="table-layout: fixed; width: 342px;">
                                                                                                            <tr>
                                                                                                                <td class=""> <img
                                                                                                                        src="' . get_site_url() . '/wp-content/uploads/2022/09/logo-coindufoncier.png"
                                                                                                                        width="342"
                                                                                                                        alt="logo"
                                                                                                                        border="0"
                                                                                                                        class=""
                                                                                                                        style="display: block; width: 100%;">
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </th>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="nl2go-responsive-hide">
                                                                <td height="20" style="font-size: 20px; line-height: 20px;">­</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="r3-c">
                                <table cellspacing="0" cellpadding="0" border="0" role="presentation" width="600" class="r4-o"
                                    style="table-layout: fixed; width: 600px;">
                                    <tr>
                                        <td valign="top" class="">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" role="presentation">
                                                <tr>
                                                    <td class="r8-c" align="center">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation"
                                                            width="100%" class="r10-o" style="table-layout: fixed; width: 100%;">
                                                            <!-- -->
                                                            <tr>
                                                                <td class="r11-i" style="background-color: #ffffff;">
                                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                                                        role="presentation">
                                                                        <tr>
                                                                            <th width="100%" valign="top" class="r6-c"
                                                                                style="font-weight: normal;">
                                                                                <table cellspacing="0" cellpadding="0" border="0"
                                                                                    role="presentation" width="100%" class="r1-o"
                                                                                    style="table-layout: fixed; width: 100%;">
                                                                                    <!-- -->
                                                                                    <tr>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                        <td valign="top" class="r7-i">
                                                                                            <table width="100%" cellspacing="0"
                                                                                                cellpadding="0" border="0"
                                                                                                role="presentation">
                                                                                                <tr>
                                                                                                    <td class="r12-c" align="center">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="570" class="r10-o"
                                                                                                            style="table-layout: fixed;">
                                                                                                            <tr>
                                                                                                                <td class="r13-i"
                                                                                                                    style="background-color: #e5f1cf; height: 5px;">
                                                                                                                    <table width="100%"
                                                                                                                        cellspacing="0"
                                                                                                                        cellpadding="0"
                                                                                                                        border="0"
                                                                                                                        role="presentation">
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <table
                                                                                                                                    width="100%"
                                                                                                                                    cellspacing="0"
                                                                                                                                    cellpadding="0"
                                                                                                                                    border="0"
                                                                                                                                    role="presentation"
                                                                                                                                    valign=""
                                                                                                                                    class="r13-i"
                                                                                                                                    height="5"
                                                                                                                                    style="border-top-style: solid; background-clip: border-box; border-top-color: #e5f1cf; border-top-width: 5px; font-size: 5px; line-height: 5px;">
                                                                                                                                    <tr>
                                                                                                                                        <td height="0"
                                                                                                                                            style="font-size: 0px; line-height: 0px;">
                                                                                                                                            ­
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </table>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td class="r14-c" align="left">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="100%" class="r15-o"
                                                                                                            style="table-layout: fixed; width: 100%;">
                                                                                                            <tr
                                                                                                                class="nl2go-responsive-hide">
                                                                                                                <td height="15"
                                                                                                                    style="font-size: 15px; line-height: 15px;">
                                                                                                                    ­</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td align="left"
                                                                                                                    valign="top"
                                                                                                                    class="r16-i nl2go-default-textstyle"
                                                                                                                    style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left;">
                                                                                                                    <div>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;">Bonjour, </span>
                                                                                                                        </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                             </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: black; font-family: Verdana, geneva, sans-serif; font-size: 18px;">Une
                                                                                                                                nouvelle
                                                                                                                                offre
                                                                                                                                lecoindufoncier
                                                                                                                                est en
                                                                                                                                ligne
                                                                                                                                pour la
                                                                                                                                commune
                                                                                                                                ' . $nom_commune . '. </span>
                                                                                                                        </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                             </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: black; font-family: Verdana, geneva, sans-serif; font-size: 18px;">Vous
                                                                                                                                pouvez
                                                                                                                                la
                                                                                                                                consulter
                                                                                                                                </span><span
                                                                                                                                style="color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;">
                                                                                                                            </span><a
                                                                                                                                href="' . get_post_permalink($json["parent"]) . '"
                                                                                                                                target="_blank"
                                                                                                                                style="color: #0092ff; text-decoration: underline;"><span
                                                                                                                                    style="color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;">ici</span></a><span
                                                                                                                                style="color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;">.</span>
                                                                                                                        </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                             </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: black; font-family: Verdana, geneva, sans-serif; font-size: 18px;">Pour
                                                                                                                                toute
                                                                                                                                demande
                                                                                                                                particulière,
                                                                                                                                n\'hésitez
                                                                                                                                pas à
                                                                                                                                nous
                                                                                                                                contacter
                                                                                                                                au 04 78 19 60 80
                                                                                                                                ou sur
                                                                                                                            </span><a
                                                                                                                                href="mailto:coindufoncier@rhone.chambagri.fr"
                                                                                                                                style="color: #0092ff; text-decoration: underline;"><span
                                                                                                                                    style="color: black; font-family: Verdana, geneva, sans-serif; font-size: 18px;">coindufoncier@rhone.chambagri.fr</span></a>
                                                                                                                        </p>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr
                                                                                                                class="nl2go-responsive-hide">
                                                                                                                <td height="15"
                                                                                                                    style="font-size: 15px; line-height: 15px;">
                                                                                                                    ­</td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </th>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="nl2go-responsive-hide">
                                                                <td height="20"
                                                                    style="font-size: 20px; line-height: 20px; background-color: #ffffff;">
                                                                    ­</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r17-c" align="center">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation"
                                                            width="100%" class="r18-o" style="table-layout: fixed; width: 100%;">
                                                            <!-- -->
                                                            <tr class="nl2go-responsive-hide">
                                                                <td height="20"
                                                                    style="font-size: 20px; line-height: 20px; background-color: #ffffff;">
                                                                    ­</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="r19-i" style="background-color: #ffffff;">
                                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                                                        role="presentation">
                                                                        <tr>
                                                                            <th width="50%" valign="middle" class="r20-c"
                                                                                style="font-weight: normal;">
                                                                                <table cellspacing="0" cellpadding="0" border="0"
                                                                                    role="presentation" width="100%" class="r1-o"
                                                                                    style="table-layout: fixed; width: 100%;">
                                                                                    <!-- -->
                                                                                    <tr>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                        <td valign="top" class="r7-i">
                                                                                            <table width="100%" cellspacing="0"
                                                                                                cellpadding="0" border="0"
                                                                                                role="presentation">
                                                                                                <tr>
                                                                                                    <td class="r8-c" align="center">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="150" class="r10-o"
                                                                                                            style="table-layout: fixed; width: 150px;">
                                                                                                            <tr
                                                                                                                class="nl2go-responsive-hide">
                                                                                                                <td height="15"
                                                                                                                    style="font-size: 15px; line-height: 15px;">
                                                                                                                    ­</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td class="r21-i"
                                                                                                                    style="font-size: 0px; line-height: 0px;">
                                                                                                                    <img src="' . get_site_url() . '/wp-content/uploads/2023/02/CA_RHONE_V_CMJN.jpg"
                                                                                                                        width="150"
                                                                                                                        border="0"
                                                                                                                        class=""
                                                                                                                        style="display: block; width: 100%;">
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </th>
                                                                            <th width="50%" valign="middle" class="r20-c"
                                                                                style="font-weight: normal;">
                                                                                <table cellspacing="0" cellpadding="0" border="0"
                                                                                    role="presentation" width="100%" class="r1-o"
                                                                                    style="table-layout: fixed; width: 100%;">
                                                                                    <!-- -->
                                                                                    <tr>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                        <td valign="top" class="r7-i">
                                                                                            <table width="100%" cellspacing="0"
                                                                                                cellpadding="0" border="0"
                                                                                                role="presentation">
                                                                                                <tr>
                                                                                                    <td class="r8-c" align="center">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="270" class="r10-o"
                                                                                                            style="table-layout: fixed; width: 270px;">
                                                                                                            <tr
                                                                                                                class="nl2go-responsive-hide">
                                                                                                                <td height="15"
                                                                                                                    style="font-size: 15px; line-height: 15px;">
                                                                                                                    ­</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td class="r21-i"
                                                                                                                    style="font-size: 0px; line-height: 0px;">
                                                                                                                    <img src="' . get_site_url() . '/wp-content/uploads/2022/09/logo-coindufoncier-e1663583317601.png"
                                                                                                                        width="270"
                                                                                                                        border="0"
                                                                                                                        class=""
                                                                                                                        style="display: block; width: 100%;">
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </th>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="nl2go-responsive-hide">
                                                                <td height="20"
                                                                    style="font-size: 20px; line-height: 20px; background-color: #ffffff;">
                                                                    ­</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="r8-c" align="center">
                                                        <table cellspacing="0" cellpadding="0" border="0" role="presentation"
                                                            width="100%" class="r10-o" style="table-layout: fixed; width: 100%;">
                                                            <!-- -->
                                                            <tr>
                                                                <td class="r22-i" style="background-color: #8eae58;">
                                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0"
                                                                        role="presentation">
                                                                        <tr>
                                                                            <th width="100%" valign="top" class="r6-c"
                                                                                style="font-weight: normal;">
                                                                                <table cellspacing="0" cellpadding="0" border="0"
                                                                                    role="presentation" width="100%" class="r1-o"
                                                                                    style="table-layout: fixed; width: 100%;">
                                                                                    <!-- -->
                                                                                    <tr>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                        <td valign="top" class="r7-i">
                                                                                            <table width="100%" cellspacing="0"
                                                                                                cellpadding="0" border="0"
                                                                                                role="presentation">
                                                                                                <tr>
                                                                                                    <td class="r14-c" align="left">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="100%" class="r15-o"
                                                                                                            style="table-layout: fixed; width: 100%;">
                                                                                                            <tr
                                                                                                                class="nl2go-responsive-hide">
                                                                                                                <td height="15"
                                                                                                                    style="font-size: 15px; line-height: 15px;">
                                                                                                                    ­</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td align="center"
                                                                                                                    valign="top"
                                                                                                                    class="r23-i nl2go-default-textstyle"
                                                                                                                    style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 18px; line-height: 1.5; text-align: center;">
                                                                                                                    <div>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: #FFFFFF; font-family: "Lucida sans unicode", "lucida grande", sans-serif; font-size: 24px;"><strong>LECOINDUFONCIER</strong></span>
                                                                                                                        </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: #FFFFFF; font-family: Verdana, geneva, sans-serif; font-size: 20px;"><strong>de
                                                                                                                                    la
                                                                                                                                    Chambre
                                                                                                                                    d\'agriculture
                                                                                                                                    du
                                                                                                                                    Rhône</strong></span>
                                                                                                                        </p>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr
                                                                                                                class="nl2go-responsive-hide">
                                                                                                                <td height="10"
                                                                                                                    style="font-size: 10px; line-height: 10px;">
                                                                                                                    ­</td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td class="r14-c" align="left">
                                                                                                        <table cellspacing="0"
                                                                                                            cellpadding="0" border="0"
                                                                                                            role="presentation"
                                                                                                            width="100%" class="r15-o"
                                                                                                            style="table-layout: fixed; width: 100%;">
                                                                                                            <tr>
                                                                                                                <td align="center"
                                                                                                                    valign="top"
                                                                                                                    class="r24-i nl2go-default-textstyle"
                                                                                                                    style="color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 18px; line-height: 1.5; text-align: center; word-wrap: break-word;">
                                                                                                                    <div>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <span
                                                                                                                                style="color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;"><u>coindufoncier@rhone.chambagri.fr</u></span>
                                                                                                                        </p>
                                                                                                                        <p
                                                                                                                            style="margin: 0;">
                                                                                                                            <a href="tel:0478196080"
                                                                                                                                target="_blank"
                                                                                                                                style="color: #0092ff; text-decoration: underline;"><span
                                                                                                                                    style="color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;"><u>0478196080</u></span></a>
                                                                                                                        </p>
                                                                                                                    </div>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </td>
                                                                                        <td class="nl2go-responsive-hide" width="15"
                                                                                            style="font-size: 0px; line-height: 1px;">­
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </th>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr class="nl2go-responsive-hide">
                                                                <td height="10"
                                                                    style="font-size: 10px; line-height: 10px; background-color: #8eae58;">
                                                                    ­</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';
                $headers = array('Content-Type: text/html; charset=UTF-8');
                // on envoie le mail
                wp_mail($email["email"], $subject, $text, $headers);
            }
        }

        // on prépare l'objet à renvoyer
        $data            = new stdClass();
        $data->response  = true;
        $data->parent_id = $json["parent"];

        // on renvoie une réponse
        echo json_encode($data);
        exit;
    }

    /**
     * Recherche des parcelle.s et / ou cuvage.s
     *
     */
    public static function searchPost($post) {
        // on prépare l'objet à renvoyer
        $data_response = new stdClass();
        // on récupère les données en $_POST
        $json                     = $post->get_json_params();
        $data                     = $json["data"];
        $data_response->data_send = $data;

        // Au niveau de la recherche
        // L'idée est de vérifier que "$data[0][results]" soit pas vide
        //var_dump($data[0][results]);
        if (isset($data[0]["filtres"]["filtres"]) && count($data[0]["filtres"]["filtres"]) > 0) {
            $type_id       = (int) $data[0]["filtres"]["type"];
            $relation      = $data[0]["filtres"]["relation"];
            $post_category = $relation;
            $meta_array    = array(
                'relation' => $relation,
            );
            $filtres = $data[0]["filtres"]["filtres"];
            foreach ($filtres as $key => $filtre) {
                if (is_array($filtre) && $data[0]["filtres"]["filtres_relation"] === "AND") {
                    $array_filtre_specifique = array(
                        'relation' => $data[0]["filtres"]["filtres_relation"],
                    );
                    foreach ($filtre as $key => $variable) {
                        array_push($array_filtre_specifique, array(
                            "key"     => $variable['name'],
                            "value"   => $variable['value'],
                            "compare" => "=",
                        ));
                    }
                    array_push($meta_array, $array_filtre_specifique);
                } else {
                    array_push($meta_array, array(
                        "key"     => $filtre['name'],
                        "value"   => $filtre['value'],
                        "compare" => "=",
                    ));
                }
            };
            // var_dump($meta_array);
            // die;
            /* Obtenir tous les articles publiés et dans la catégorie sélectionnée. */
            $posts = get_posts(array(
                'numberposts'  => -1,
                'post_type'    => 'post',
                'post_status'  => 'publish',
                'category__in' => array(
                    '13',
                    '14',
                    '20',
                ),
                'meta_query'   => $meta_array,
            ));
        } else {
            if (isset($data[0]["filtres"]["type"])) {
                $posts = get_posts(array(
                    'numberposts'  => -1,
                    'post_type'    => 'post',
                    'post_status'  => 'publish',
                    'category__in' => array(
                        $data[0]["filtres"]["type"],
                    ),
                ));
            } else {
                /* Obtenir tous les articles publiés et dans la catégorie sélectionnée. */
                $posts = get_posts(array(
                    'numberposts'  => -1,
                    'post_type'    => 'post',
                    'post_status'  => 'publish',
                    'category__in' => array(
                        '13',
                        '14',
                        '20',
                    ),
                ));
            }
        };

        // on prépare les objets à renvoyer
        # code...
        $data_response->results = array();
        foreach ($posts as $key => $post) {
            $id       = $post->ID;
            $offre_id = get_field('parent', $id);
            if ($offre_id !== "") {
                $offre_title = get_the_title($offre_id);
                $category    = get_the_category($id);
                $category_id = $category[0]->term_id;
                if (get_post_status($offre_id) !== false) {
                    // The post exist
                    if ($category_id === 13) { // c'est un cuvage
                       /* $commune        = get_field('lieu_code_insee', $id);
                        $coordonees_gps_commune = self::getCorrespondanceGPS($commune);
                        $coordonees_gps = get_field('cuvage_coordonnees_gps', $id);
                        //var_dump($coordonees_gps);
                        if ($coordonees_gps_commune !== false && $coordonees_gps === null ) {
                           // $coordonees_gps = $commune;
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else if($coordonees_gps !== null && $coordonees_gps_commune !== false ){
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        }else if($coordonees_gps !== null && $coordonees_gps_commune === false ){
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        }else {
                           // $coordonees_gps = get_field('ajouter_une_offre_complete_coordonnees_gps', $id);
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        }*/
                        $commune        = get_field('lieu_code_insee', $id);
                        $coordonees_gps = self::getCorrespondanceGPS($commune);
                        if ($coordonees_gps !== false) {
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else {
                            $coordonees_gps = get_field('cuvage_coordonnees_gps', $id);
                            $commune_name = false;
                        }
                        array_push($data_response->results, array(
                            "titre"          => "Cuvage",
                            "id"             => $id,
                            "category_id"    => $category_id,
                            "Offre_id"       => $offre_id,
                            "Offre_titre"    => $offre_title,
                            "Commune"        => $commune,
                            "Commune_name"   => $commune_name,
                            "Coordonees_gps" => $coordonees_gps,
                            "Offre_url"      => get_post_permalink($offre_id),
                        ));
                    } else if ($category_id === 20) { // c'est une offre complète
                        //TODO Map les champs ACF
                        $offre_title    = get_the_title($id);
                        $category       = get_the_category($id);
                        $category_id    = $category[0]->term_id;
                        $commune        = get_field('ajouter_une_offre_complete_code_insee', $id);
                        $urlExterne     = get_field('ajouter_une_offre_complete_url_de_loffre', $id);
                        $typeOffre      = get_field('ajouter_une_offre_complete_type_doffre', $id);
                        $coordonees_gps_commune = self::getCorrespondanceGPS($commune);
                        $coordonees_gps = get_field('ajouter_une_offre_complete_coordonnees_gps', $id);

                        if ($coordonees_gps_commune !== false && $coordonees_gps === null ) {
                            $coordonees_gps = $coordonees_gps_commune;
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else if($coordonees_gps !== null && $coordonees_gps_commune !== false ){
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        }else if($coordonees_gps !== null && $coordonees_gps_commune === false ){
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        }else {
                           // $coordonees_gps = get_field('ajouter_une_offre_complete_coordonnees_gps', $id);
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        }


                        array_push($data_response->results, array(
                            "titre"          => "Offre complète",
                            "id"             => $id,
                            "category_id"    => $category_id,
                            "Offre_id"       => $id,
                            "Type_offre"     => $typeOffre,
                            "Offre_titre"    => $offre_title,
                            "Commune"        => $commune,
                            "Commune_name"   => $commune_name,
                            "Coordonees_gps" => $coordonees_gps,
                            //"Offre_url"      => get_post_permalink($id),
                            "Offre_url"      => $urlExterne,
                        ));
                    } else { // c'est une parcelle
                        $commune        = get_field('ajouter_une_parcelle_code_insee', $id);
                        $cepage         = get_field('vigne_et_vin_cepage', $id);
                        $cession        = get_field('complements_type_de_cession_parcelle', $id);
                        $coordonees_gps = get_field('ajouter_une_parcelle_coordonees_gps', $id);
                        if ($coordonees_gps !== false) {
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else {
                            $commune_name = false;
                        }
                        array_push($data_response->results, array(
                            "titre"          => "Vigne",
                            "id"             => $id,
                            "category_id"    => $category_id,
                            "Offre_id"       => $offre_id,
                            "Offre_titre"    => $offre_title,
                            "Commune"        => $commune,
                            "Cepage"         => $cepage,
                            "Cession"        => $cession,
                            "Coordonees_gps" => $coordonees_gps,
                            "Commune_name"   => $commune_name,
                            "Offre_url"      => get_post_permalink($offre_id),
                        ));
                    }
                }
            } else {
                //var_dump('non');
            }
        };
        //die;

        //On va vérifié qu'en recherchant un "post", on obtient un ou plusieurs résultat(s)
        //Si pas de résultat(s)
        if (empty($data_response->results)) {
            //On définit la réponse à "false";
            $data_response->response = false;
            // on renvoie la réponse
            echo json_encode($data_response);
        } else {
            //On définit la réponse à "true";
            $data_response->response = true;
            // on renvoie une réponse
            echo json_encode($data_response);
        }
        exit;
    }

    /**
     * TODO Réinitialisation d'un filtre sur la page "Je cherche"
     *
     */
    public static function reinitPost($post) {
        // on prépare l'objet à renvoyer
        $data_response = new stdClass();
        // on récupère les données en $_POST
        $json                     = $post->get_json_params();
        $data                     = $json["data"];
        $data_response->data_send = $data;

        /* Obtenir tous les articles publiés et dans la catégorie sélectionnée. */
        $posts = get_posts(array(
            'numberposts'  => -1,
            'post_type'    => 'post',
            'post_status'  => 'publish',
            'category__in' => array(
                '13',
                '14',
                '20',
            ),
        ));

        // on prépare les objets à renvoyer
        # code...
        $data_response->results = array();
        foreach ($posts as $key => $post) {
            $id       = $post->ID;
            $offre_id = get_field('parent', $id);
            if ($offre_id !== "") {
                $offre_title = get_the_title($offre_id);
                $category    = get_the_category($id);
                $category_id = $category[0]->term_id;
                if (get_post_status($offre_id) !== false) {
                    // The post exist
                    if ($category_id === 13) { // c'est un cuvage
                        $commune        = get_field('lieu_code_insee', $id);
                        $coordonees_gps = self::getCorrespondanceGPS($commune);
                        if ($coordonees_gps !== false) {
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else {
                            $commune_name = false;
                        }
                        //$commune        = get_field('lieu_code_insee', $id);
                        //$coordonees_gps_commune = self::getCorrespondanceGPS($commune);
                        //$coordonees_gps = get_field('cuvage_coordonnees_gps', $id);
                        array_push($data_response->results, array(
                            "titre"          => "Cuvage",
                            "id"             => $id,
                            "category_id"    => $category_id,
                            "Offre_id"       => $offre_id,
                            "Offre_titre"    => $offre_title,
                            "Commune"        => $commune,
                            "Commune_name"   => $commune_name,
                            "Coordonees_gps" => $coordonees_gps,
                            "Offre_url"      => get_post_permalink($offre_id),
                        ));
                    } else if ($category_id === 20) { // c'est une offre complète
                        //TODO Map les champs ACF
                        $offre_title    = get_the_title($id);
                        $category       = get_the_category($id);
                        $category_id    = $category[0]->term_id;
                        $commune        = get_field('ajouter_une_offre_complete_code_insee', $id);
                        $urlExterne     = get_field('ajouter_une_offre_complete_url_de_loffre', $id);
                        $typeOffre      = get_field('ajouter_une_offre_complete_type_doffre', $id);
                        $coordonees_gps = self::getCorrespondanceGPS($commune);
                        if ($coordonees_gps !== false) {
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else {
                            $coordonees_gps = get_field('ajouter_une_offre_complete_coordonnees_gps', $id);
                            $commune_name = false;
                        }
                        array_push($data_response->results, array(
                            "titre"          => "Offre complète",
                            "id"             => $id,
                            "category_id"    => $category_id,
                            "Offre_id"       => $id,
                            "Type_offre"     => $typeOffre,
                            "Offre_titre"    => $offre_title,
                            "Commune"        => $commune,
                            "Commune_name"   => $commune_name,
                            "Coordonees_gps" => $coordonees_gps,
                            //"Offre_url"      => get_post_permalink($id),
                            "Offre_url"      => $urlExterne,
                        ));
                    } else { // c'est une parcelle
                        $commune        = get_field('ajouter_une_parcelle_code_insee', $id);
                        $cepage         = get_field('vigne_et_vin_cepage', $id);
                        $cession        = get_field('complements_type_de_cession_parcelle', $id);
                        $coordonees_gps = get_field('ajouter_une_parcelle_coordonees_gps', $id);
                        if ($coordonees_gps !== false) {
                            $commune_name = self::getCommuneName($category_id, $id, $commune);
                        } else {
                            $commune_name = false;
                        }
                        array_push($data_response->results, array(
                            "titre"          => "Vigne",
                            "id"             => $id,
                            "category_id"    => $category_id,
                            "Offre_id"       => $offre_id,
                            "Offre_titre"    => $offre_title,
                            "Commune"        => $commune,
                            "Cepage"         => $cepage,
                            "Cession"        => $cession,
                            "Coordonees_gps" => $coordonees_gps,
                            "Commune_name"   => $commune_name,
                            "Offre_url"      => get_post_permalink($offre_id),
                        ));
                    }
                }
            }
        };
        $data_response->response = true;
        // on renvoie une réponse
        echo json_encode($data_response);
        exit;
    }

    /**
     * It returns the GPS coordinates of a given postal code
     *
     * @param code_postal The postal code you want to get the GPS coordinates for.
     *
     * @return The GPS coordinates of the city.
     */
    public static function getCorrespondanceGPS($code_postal) {
        $request = "SELECT * FROM correspondance_codepostal_gps WHERE code_postal='" . $code_postal . "'"; // code insee
        $results = FLK_Bdd_request::flk_query($request);
        if (isset($results[0]["coordonnees"])) {
            return $results[0]["coordonnees"];
        } else {
            return false;
        }
    }

/**
 * Il renvoie le nom d'une commune (ville) à partir d'un code postal (code postal) dans un type de
 * poste personnalisé
 *
 * @param category_id l'ID de la catégorie dont vous souhaitez obtenir les messages
 * @param id l'identifiant du poste
 * @param code_postal le code postal de la commune
 *
 * @return Le nom de la commune.
 */
    public static function getCommuneName($category_id, $id, $code_postal) {
        if ($category_id === 13) { // c'est un cuvage
            $sous_groupe_array = array(
                "field_62ebbd17e30df",
            );
        } else { // c'est une parcelle
            $sous_groupe_array = array(
                "field_62eb9e4c0fb76",
            ); // liste des groupes de champs pour l'ajout d'une parcelle
        }
        foreach ($sous_groupe_array as $key => $sous_groupe) {
            $commune_name = null;
            if (have_rows($sous_groupe, $id)) { // Si le groupe de champ ajouter_une_parcelle existe
                $row = the_row();
                foreach ($row as $key => $field) { // on récupère les infos de chaque champs du sous groupe
                    $sub_field_objet = get_sub_field_object($key);
                    $type            = $sub_field_objet["type"];
                    if ($code_postal !== NULL) {
                        //var_dump($code_postal);
                        if (isset($sub_field_objet["choices"][$code_postal])) {
                            $commune_name = $sub_field_objet["choices"][$code_postal];
                        }
                    }
                }
            }
        }
        ;
// $acf_groupe_object = get_field_objects($id);
        return $commune_name;
    }

}