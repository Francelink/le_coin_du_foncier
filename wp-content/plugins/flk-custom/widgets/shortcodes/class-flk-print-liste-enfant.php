<?php

/**
 * Exemple de fichier php pour la création d'un shortcode
 */

defined('ABSPATH') || exit;

/**
 * Shortcode cart class.
 */
class FLK_Shortcode_Print_Liste_Enfant
{

    /**
     * Output the Shortcode Exemplee.
     *
     * @param array $atts Shortcode attributes.
     */
    public static function output($atts)
    {
        global $post;
        $data           = array();
        $data["inputs"] = array();
        /// on récupère le statut de l'article
        $data["statut"] = get_post_status($post);
        // $_GET['post_id']=$post->ID;
        // td head
        // td body
        $data["table"]           = array();
        $data["table"]["tdbody"] = array();
        global $post;
        $request = "SELECT post_id FROM wp_postmeta WHERE meta_key='parent' AND meta_value='" . $post->ID . "'";
        $query   = FLK_Bdd_request::flk_query($request);
        //var_dump($query);
        if (count($query) > 0) {
            foreach ($query as $k => $value) {
                if (get_post_type($value["post_id"]) === 'post') {
                    // l'offre à des parcelles ou des cuvages
                    $enfant   = $value["post_id"];
                    $category = get_the_category($enfant);
                    if (count($category) >= 1) {
                        $category_id                 = $category[0]->term_id;
                        $data["table"]["tdbody"][$k] = array();
                        if ($category_id === 13) { // c'est un cuvage
                            $sous_groupe_array = array(
                                "field_62ebbd17e30df",
                                "field_62ebbd8571422",
                                "field_62ebbfabfdd11",
                            );
                            $commune                                       = get_field("lieu_code_insee", $enfant);
                            $data["table"]["tdbody"][$k]["Coordonees_gps"] = FLK_Ajax_Api_Controller::getCorrespondanceGPS($commune);
                        } else { // c'est une parcelle
                            $sous_groupe_array = array(
                                "field_62eb9e4c0fb76",
                                "field_62ebb6fe13632",
                                "field_62ebb79d92dd5",
                                "field_62ebbbed58c63",
                            ); // liste des groupes de champs pour l'ajout d'une parcelle
                        }
                        $acf_groupe_object = get_field_objects($enfant);
                        foreach ($sous_groupe_array as $key => $sous_groupe) {
                            if (have_rows($sous_groupe, $enfant)) { // Si le groupe de champ ajouter_une_parcelle existe
                                $field_group                      = get_field_object($sous_groupe, $enfant);
                                $title                            = $field_group["label"]; // on récupère le nom du sous group
                                $data["inputs"][$k][$sous_groupe] = array(
                                    'titre'  => $title,
                                    'champs' => array(),
                                ); // on crée un array pour envoyé les champs par sous groupe vers le front
                                $row = the_row();
                                foreach ($row as $key => $field) { // on récupère les infos de chaque champs du sous groupe
                                    if ($field !== 0) {
                                        $sub_field_objet = get_sub_field_object($key);
                                        $type            = $sub_field_objet["type"];
                                        switch ($type) {
                                            case 'radio':
                                                if (isset($sub_field_objet["choices"][$field])) {
                                                    $data["inputs"][$k][$sous_groupe]['champs'][$sub_field_objet["menu_order"]] = array(
                                                        "key"   => $sub_field_objet["key"],
                                                        "name"  => $field,
                                                        "label" => $sub_field_objet["label"],
                                                        "value" => $sub_field_objet["choices"][$field],
                                                    );
                                                    array_push($data["table"]["tdbody"][$k], array(
                                                        "key"   => $sub_field_objet["key"],
                                                        "name"  => $field,
                                                        "label" => $sub_field_objet["label"],
                                                        "value" => $sub_field_objet["choices"][$field],
                                                    ));
                                                } else {
                                                    array_push($data["table"]["tdbody"][$k], array(
                                                        "key"   => "",
                                                        "name"  => "",
                                                        "label" => "",
                                                        "value" => "",
                                                    ));
                                                }
                                                // array_push($data["table"]["tdbody"][$k], $sub_field_objet["choices"][$field]);
                                                break;
                                            case 'select':
                                                if (isset($sub_field_objet["choices"][$field])) {
                                                    $data["inputs"][$k][$sous_groupe]['champs'][$sub_field_objet["menu_order"]] = array(
                                                        "key"   => $sub_field_objet["key"],
                                                        "name"  => $field,
                                                        "label" => $sub_field_objet["label"],
                                                        "value" => $sub_field_objet["choices"][$field],
                                                    );
                                                    array_push($data["table"]["tdbody"][$k], array(
                                                        "key"   => $sub_field_objet["key"],
                                                        "name"  => $field,
                                                        "label" => $sub_field_objet["label"],
                                                        "value" => $sub_field_objet["choices"][$field],
                                                    ));
                                                    // array_push($data["table"]["tdbody"][$k], $sub_field_objet["choices"][$field]);
                                                } else {
                                                    array_push($data["table"]["tdbody"][$k], array(
                                                        "key"   => "",
                                                        "name"  => "",
                                                        "label" => "",
                                                        "value" => "",
                                                    ));
                                                }
                                                break;
                                            case 'checkbox':
                                                if (is_array($field)) { // on a plusieur valeur 
                                                    $array_value = array();
                                                    foreach ($field as $key => $value) {
                                                        if (isset($sub_field_objet["choices"][$value])) {
                                                            array_push($array_value, $sub_field_objet["choices"][$value]);
                                                        }
                                                    }
                                                    $data["inputs"][$k][$sous_groupe]['champs'][$sub_field_objet["menu_order"]] = array(
                                                        "key"   => $sub_field_objet["key"],
                                                        "name"  =>  $sub_field_objet["key"],
                                                        "label" => $sub_field_objet["label"],
                                                        "value" => $array_value,
                                                    );
                                                    array_push($data["table"]["tdbody"][$k], array(
                                                        "key"   => $sub_field_objet["key"],
                                                        "name"  =>  $sub_field_objet["key"],
                                                        "label" => $sub_field_objet["label"],
                                                        "value" => $array_value,
                                                    ));
                                                } else { // on a un valeur 
                                                    # code...
                                                    if (isset($sub_field_objet["choices"][$field])) {
                                                        $data["inputs"][$k][$sous_groupe]['champs'][$sub_field_objet["menu_order"]] = array(
                                                            "key"   => $sub_field_objet["key"],
                                                            "name"  => $field,
                                                            "label" => $sub_field_objet["label"],
                                                            "value" => $sub_field_objet["choices"][$field],
                                                        );
                                                        array_push($data["table"]["tdbody"][$k], array(
                                                            "key"   => $sub_field_objet["key"],
                                                            "name"  => $field,
                                                            "label" => $sub_field_objet["label"],
                                                            "value" => $sub_field_objet["choices"][$field],
                                                        ));
                                                        // array_push($data["table"]["tdbody"][$k], $sub_field_objet["choices"][$field]);
                                                    } else {
                                                        array_push($data["table"]["tdbody"][$k], array(
                                                            "key"   => "",
                                                            "name"  => "",
                                                            "label" => "",
                                                            "value" => "",
                                                        ));
                                                    }
                                                }
                                                break;
                                            default:
                                                $data["inputs"][$k][$sous_groupe]['champs'][$sub_field_objet["menu_order"]] = array(
                                                    "key"   => $sub_field_objet["key"],
                                                    "name"  => $field,
                                                    "label" => $sub_field_objet["label"],
                                                    "value" => $field,
                                                );
                                                array_push($data["table"]["tdbody"][$k], array(
                                                    "key"   => $sub_field_objet["key"],
                                                    "name"  => $field,
                                                    "label" => $sub_field_objet["label"],
                                                    "value" => $field,
                                                ));
                                                // array_push($data["table"]["tdbody"][$k], $field);
                                                break;
                                        };
                                    }
                                }
                            } else {
                                $data["inputs"][$k] = "Contenu sans infos id =" . $enfant;
                                array_push($data["table"]["tdbody"][$k], "Contenu sans infos id =" . $enfant);
                            };
                        };
                    }
                }
            };
        } else { // l'offre n'à des parcelles ou des cuvages
            var_dump(0);
        };
        if (isset($category_id)) {
            $data["table"]["tdhead"] = self::getTdHead($category_id);
        }
        FLK_Render_template::renderTemplate('widgets/shortcodes/print-liste-enfant', $data);
    }

    public static function getTdHead($category_id)
    {
        $data = array();
        if ($category_id === 13) { // c'est un cuvage
            $enfant            = 1261; // article de base pour récuperer les champs
            $sous_groupe_array = array(
                "lieu",
                "cuvage",
                "complement",
            ); // liste des groupes de champs pour l'ajout d'une parcelle
        } else { // c'est une parcelle
            $enfant            = 1185;
            $sous_groupe_array = array(
                "ajouter_une_parcelle",
                "terrain",
                "vigne_et_vin",
                "complements",
            ); // liste des groupes de champs pour l'ajout d'une parcelle
        }
        foreach ($sous_groupe_array as $key => $sous_groupe) {
            if (have_rows($sous_groupe, $enfant)) { // Si le groupe de champ ajouter_une_parcelle existe
                $row = the_row();
                foreach ($row as $key => $field) { // on récupère les infos de chaque champs du sous groupe
                    $sub_field_objet = get_sub_field_object($key);
                    array_push($data, $sub_field_objet["label"]);
                }
            }
        };
        //var_dump($data[6]);
        return $data;
    }
}
