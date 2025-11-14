<?php

/**
 * Setup Class
 * read : https://www.php.net/manual/fr/language.oop5.php
 * A copier coller pour la création d'une classe
 *
 */
defined('ABSPATH') || exit;
include_once ABSPATH . '/wp-content/plugins/elementor-pro/modules/forms/classes/action-base.php';

/**
 * On déclare la class si elle est existe
 *
 */
if (class_exists('FLK_Action_Obtenir_Les_Infos', false)) {
    return new FLK_Action_Obtenir_Les_Infos();
}

/**
 * FLK_Produit_Simple Class.
 */
class FLK_Action_Obtenir_Les_Infos extends \ElementorPro\Modules\Forms\Classes\Action_Base
{
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
    public function __construct()
    {
        add_action('wp_mail_failed', array($this, 'onMailError'));
    }

    public function get_name()
    {
        return 'obtenir_les_infos';
    }

    public function get_label()
    {
        return 'obtenir_les_infos';
    }

    public function register_settings_section($widget)
    {

        /*$widget->start_controls_section(
    'custom_action_section',
    [
    'label' => esc_html__( 'Custom Action', 'plugin-name' ),
    'condition' => [
    'submit_actions' => $this->get_name(),
    ],
    ]
    );*/
    }

    public function run($record, $ajax_handler)
    {

        // Pour récupere les infos du formulaire
        // var_dump($_POST);die;
        // $post_id    = $_POST['queried_id'];


        $url = $_POST["referrer"] ?? '';

        if (preg_match('/offre-n-(\d+)/', $url, $matches)) {
            $post_id = $matches[1];
        } else {
            return;
        }

        $categories = get_the_category($post_id);
        foreach ($categories as $category) {
            if ($category->term_id === 12) {
                $cat = "Vous venez de demander les coordonnées du vigneron ou du propriétaire du cuvage"; // c'est une cuve
            } elseif ($category->term_id === 11) {
                $cat = "Vous venez de demander les coordonnées du vigneron ou du propriétaire des vignes"; // c'est une parcelle
            }
        };

        $post_email = $_POST["form_fields"]["email"];
        /// Pour recuperer les infos de l'offres
        $data_offre = array();
        if (have_rows('field_62e10938f0866', $post_id)) {
            $row = the_row();
            foreach ($row as $key => $field) {
                $sub_field_objet = get_sub_field_object($key);
                $sub_field_value = get_sub_field($key);
                $type            = $sub_field_objet["type"];
                if ($key !== "field_62e10bd7f1f36") { // On ignore la date de naissance
                    if (is_string($type)) {
                        switch ($type) {
                            case 'radio':
                                $data_offre[$sub_field_objet["menu_order"]] = array(
                                    "name"  => $field,
                                    "label" => $sub_field_objet["label"],
                                    "value" => $sub_field_objet["choices"][$field],
                                );
                                break;
                            case 'select':
                                $data_offre[$sub_field_objet["menu_order"]] = array(
                                    "name"  => $field,
                                    "label" => $sub_field_objet["label"],
                                    "value" => $sub_field_objet["choices"][$field],
                                );
                                break;
                            default:
                                $data_offre[$sub_field_objet["menu_order"]] = array(
                                    "name"  => $field,
                                    "label" => $sub_field_objet["label"],
                                    "value" => $field,
                                );
                                break;
                        }
                    }
                }
            }
            // On isole le Nom
            $post_nom = $data_offre[0]['name'];

            // On isole le Prenom
            $post_prenom = $data_offre[1]['name'];

            // On isole le Phone
            $post_phone = $data_offre[6]['name'];

            // On isole le Email
            $user_email = $data_offre[7]['name'];

            //var_dump($post_nom, $post_prenom, $post_phone, $post_email);die;

        }

        // Pour envoyer le mail c'est dans wp_mail();
        $subject = 'Votre demande d\'informations pour l\'offre';
        // $text    = "<div>";
        // // on va mettre un titre
        // $text .= "<H2> Votre demande d'information pour l'offre n°" . $post_id . "</H2>";
        // $text .= '<H4> retrouver l\'offre <a href="' . get_permalink($post_id) . '">ici</a></H4>';
        // // on va lister les élements
        // $text .= "</br>";
        // $text .= "<ul>";
        // foreach ($data_offre as $key => $value) {
        //     $text .= '<li>' . $value['label'] . ' : ' . $value['value'] . '</li>';
        //     //var_dump($value['label']);
        // }
        // $text .= "</ul>";
        // $text .= "</div>";

        $text = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
        <html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'><head><meta http-equiv='X-UA-Compatible' content='IE=edge'><meta name='format-detection' content='telephone=no'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title></title><style type='text/css' emogrify='no'>#outlook a { padding:0; } .ExternalClass { width:100%; } .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } table td { border-collapse: collapse; mso-line-height-rule: exactly; } .editable.image { font-size: 0 !important; line-height: 0 !important; } .nl2go_preheader { display: none !important; mso-hide:all !important; mso-line-height-rule: exactly; visibility: hidden !important; line-height: 0px !important; font-size: 0px !important; } body { width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; } img { outline:none; text-decoration:none; -ms-interpolation-mode: bicubic; } a img { border:none; } table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; } th { font-weight: normal; text-align: left; } *[class='gmail-fix'] { display: none !important; } </style><style type='text/css' emogrify='no'> @media (max-width: 600px) { .gmx-killpill { content: ' \03D1';} } </style><style type='text/css' emogrify='no'>@media (max-width: 600px) { .gmx-killpill { content: ' \03D1';} .r0-c { box-sizing: border-box !important; width: 100% !important } .r1-o { border-style: solid !important; width: 100% !important } .r2-i { background-color: #ffffff !important } .r3-c { box-sizing: border-box !important; text-align: center !important; valign: top !important; width: 320px !important } .r4-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 320px !important } .r5-i { padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 15px !important; padding-top: 20px !important } .r6-c { box-sizing: border-box !important; display: block !important; valign: top !important; width: 100% !important } .r7-i { padding-left: 0px !important; padding-right: 0px !important } .r8-c { box-sizing: border-box !important; text-align: center !important; valign: top !important; width: 100% !important } .r9-o { background-size: auto !important; border-style: solid !important; margin: 0 auto 0 auto !important; width: 100% !important } .r10-o { border-style: solid !important; margin: 0 auto 0 auto !important; width: 100% !important } .r11-i { background-color: #ffffff !important; padding-bottom: 20px !important; padding-left: 15px !important; padding-right: 15px !important; padding-top: 0px !important } .r12-c { box-sizing: border-box !important; text-align: center !important; width: 100% !important } .r13-i { background-color: #e5f1cf !important; padding-bottom: 0px !important; padding-top: 0px !important } .r14-c { box-sizing: border-box !important; text-align: left !important; valign: top !important; width: 100% !important } .r15-o { border-style: solid !important; margin: 0 auto 0 0 !important; width: 100% !important } .r16-i { padding-bottom: 15px !important; padding-top: 15px !important; text-align: left !important } .r17-i { background-color: #8eae58 !important; padding-bottom: 10px !important; padding-left: 15px !important; padding-right: 15px !important; padding-top: 0px !important } .r18-i { padding-bottom: 10px !important; padding-top: 15px !important; text-align: center !important } .r19-i { padding-bottom: 0px !important; padding-top: 0px !important; text-align: center !important } body { -webkit-text-size-adjust: none } .nl2go-responsive-hide { display: none } .nl2go-body-table { min-width: unset !important } .mobshow { height: auto !important; overflow: visible !important; max-height: unset !important; visibility: visible !important; border: none !important } .resp-table { display: inline-table !important } .magic-resp { display: table-cell !important } } </style><!--[if !mso]><!--><style type='text/css' emogrify='no'>@import url('https://fonts.googleapis.com/css2?family=Montserrat'); </style><!--<![endif]--><style type='text/css'>p, h1, h2, h3, h4, ol, ul { margin: 0; } a, a:link { color: #0092ff; text-decoration: underline } .nl2go-default-textstyle { color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5 } .default-button { border-radius: 4px; color: #ffffff; font-family: arial,helvetica,sans-serif; font-size: 16px; font-style: normal; font-weight: normal; line-height: 1.15; text-decoration: none; width: 50% } .default-heading1 { color: #1F2D3D; font-family: arial,helvetica,sans-serif; font-size: 36px } .default-heading2 { color: #1F2D3D; font-family: arial,helvetica,sans-serif; font-size: 32px } .default-heading3 { color: #1F2D3D; font-family: arial,helvetica,sans-serif; font-size: 24px } .default-heading4 { color: #1F2D3D; font-family: arial,helvetica,sans-serif; font-size: 18px } a[x-apple-data-detectors] { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; } .no-show-for-you { border: none; display: none; float: none; font-size: 0; height: 0; line-height: 0; max-height: 0; mso-hide: all; overflow: hidden; table-layout: fixed; visibility: hidden; width: 0; } </style><!--[if mso]><xml> <o:OfficeDocumentSettings> <o:AllowPNG/> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><![endif]--><style type='text/css'>a:link{color: #0092ff; text-decoration: underline}</style></head><body text='#3b3f44' link='#0092ff' yahoo='fix' style=''> <table cellspacing='0' cellpadding='0' border='0' role='presentation' class='nl2go-body-table' width='100%' style='width: 100%;'><tr><td align='' class='r0-c'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r1-o' style='table-layout: fixed; width: 100%;'><!-- --><tr><td valign='top' class='r2-i' style='background-color: #ffffff;'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><td class='r3-c' align='center'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='600' class='r4-o' style='table-layout: fixed; width: 600px;'><!-- --><tr class='nl2go-responsive-hide'><td height='20' style='font-size: 20px; line-height: 20px;'>­</td> </tr><tr><td class='r5-i'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><th width='100%' valign='top' class='r6-c' style='font-weight: normal;'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r1-o' style='table-layout: fixed; width: 100%;'><!-- --><tr><td class='nl2go-responsive-hide' width='15' style='font-size: 0px; line-height: 1px;'>­ </td> <td valign='top' class='r7-i'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><td class='r8-c' align='center'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='342' class='r9-o' style='table-layout: fixed; width: 342px;'><tr><td class=''> <img src='" . get_site_url() . "/wp-content/uploads/2022/09/logo-coindufoncier.png' width='342' alt='logo' border='0' class='' style='display: block; width: 100%;'></td> </tr></table></td> </tr></table></td> <td class='nl2go-responsive-hide' width='15' style='font-size: 0px; line-height: 1px;'>­ </td> </tr></table></th> </tr></table></td> </tr><tr class='nl2go-responsive-hide'><td height='20' style='font-size: 20px; line-height: 20px;'>­</td> </tr></table></td> </tr></table></td> </tr></table></td> </tr><tr><td align='center' class='r3-c'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='600' class='r4-o' style='table-layout: fixed; width: 600px;'><tr><td valign='top' class=''> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><td class='r8-c' align='center'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r10-o' style='table-layout: fixed; width: 100%;'><!-- --><tr><td class='r11-i' style='background-color: #ffffff;'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><th width='100%' valign='top' class='r6-c' style='font-weight: normal;'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r1-o' style='table-layout: fixed; width: 100%;'><!-- --><tr><td class='nl2go-responsive-hide' width='15' style='font-size: 0px; line-height: 1px;'>­ </td> <td valign='top' class='r7-i'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><td class='r12-c' align='center'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='570' class='r10-o' style='table-layout: fixed;'><tr><td class='r13-i' style='background-color: #e5f1cf; height: 5px;'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><td><table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation' valign='' class='r13-i' height='5' style='border-top-style: solid; background-clip: border-box; border-top-color: #e5f1cf; border-top-width: 5px; font-size: 5px; line-height: 5px;'><tr><td height='0' style='font-size: 0px; line-height: 0px;'>­</td> </tr></table></td> </tr></table></td> </tr></table></td> </tr><tr><td class='r14-c' align='left'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r15-o' style='table-layout: fixed; width: 100%;'><tr class='nl2go-responsive-hide'><td height='15' style='font-size: 15px; line-height: 15px;'>­</td> </tr><tr><td align='left' valign='top' class='r16-i nl2go-default-textstyle' style='color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 16px; line-height: 1.5; text-align: left; word-wrap: break-word;'> <div><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Bonjour, </span></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>$cat.</span></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Proposées sur lecoindufoncier pour l'offre numéro </span><a href='" . get_site_url() . "/offre-n-$post_id' target='_blank' style='color: #0092ff; text-decoration: underline;'><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>$post_id</span></a><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>.</span></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Il s'agit de Monsieur (ou Madame) : </span><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>$post_prenom</span><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'> $post_nom . </span></p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Le mail est :  </span><a href='mailto:$user_email' target='_blank' style='color: #0092ff; text-decoration: underline;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>$user_email</span></a></p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Le numéro de téléphone est : $post_phone</span></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Nous vous rappelons que si la mise en relation se conclut positivement, vous devez suivre le contrôle des structures. Pour en savoir plus, <a href='https://www.rhone.gouv.fr/Politiques-publiques/Agriculture-foret-et-developpement-rural/Foncier/Controle-des-structures' target='_blank' style='color: #0092ff; text-decoration: underline;'><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;'>cliquez ici</span></a></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>De plus, pour votre information, la SAFER a la possibilité de préempter des terres lors de projets de vente de biens ruraux. Pour en savoir plus,</span><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 18px;'> </span><a href='https://www.safer.fr/les-safer/le-droit-de-preemption/' target='_blank' style='color: #0092ff; text-decoration: underline;'><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;'>cliquez ici</span></a><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;'> </span></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Nous vous rappelons enfin de nous informer de l'aboutissement de la mise en relation pour pouvoir retirer l'offre du site et éviter de futures candidatures.</span></p><p style='margin: 0;'> </p><p style='margin: 0;'><span style='color: #292929; font-family: Verdana, geneva, sans-serif; font-size: 18px;'>Nous vous en remercions ! </span></p></div> </td> </tr><tr class='nl2go-responsive-hide'><td height='15' style='font-size: 15px; line-height: 15px;'>­</td> </tr></table></td> </tr></table></td> <td class='nl2go-responsive-hide' width='15' style='font-size: 0px; line-height: 1px;'>­ </td> </tr></table></th> </tr></table></td> </tr><tr class='nl2go-responsive-hide'><td height='20' style='font-size: 20px; line-height: 20px; background-color: #ffffff;'>­</td> </tr></table></td> </tr><tr><td class='r8-c' align='center'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r10-o' style='table-layout: fixed; width: 100%;'><!-- --><tr><td class='r17-i' style='background-color: #8eae58;'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><th width='100%' valign='top' class='r6-c' style='font-weight: normal;'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r1-o' style='table-layout: fixed; width: 100%;'><!-- --><tr><td class='nl2go-responsive-hide' width='15' style='font-size: 0px; line-height: 1px;'>­ </td> <td valign='top' class='r7-i'> <table width='100%' cellspacing='0' cellpadding='0' border='0' role='presentation'><tr><td class='r14-c' align='left'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r15-o' style='table-layout: fixed; width: 100%;'><tr class='nl2go-responsive-hide'><td height='15' style='font-size: 15px; line-height: 15px;'>­</td> </tr><tr><td align='center' valign='top' class='r18-i nl2go-default-textstyle' style='color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 18px; line-height: 1.5; text-align: center;'> <div><p style='margin: 0;'><span style='color: #FFFFFF; font-family: 'Lucida sans unicode', 'lucida grande', sans-serif; font-size: 24px;'><strong>LECOINDUFONCIER</strong></span></p><p style='margin: 0;'><span style='color: #FFFFFF; font-family: Verdana, geneva, sans-serif; font-size: 20px;'><strong>de la Chambre d'agriculture du Rhône</strong></span></p></div> </td> </tr><tr class='nl2go-responsive-hide'><td height='10' style='font-size: 10px; line-height: 10px;'>­</td> </tr></table></td> </tr><tr><td class='r14-c' align='left'> <table cellspacing='0' cellpadding='0' border='0' role='presentation' width='100%' class='r15-o' style='table-layout: fixed; width: 100%;'><tr><td align='center' valign='top' class='r19-i nl2go-default-textstyle' style='color: #3b3f44; font-family: arial,helvetica,sans-serif; font-size: 18px; line-height: 1.5; text-align: center; word-wrap: break-word;'> <div><p style='margin: 0;'><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;'><u>coindufoncier@rhone.chambagri.fr</u></span></p><p style='margin: 0;'><a href='tel:0478196080' target='_blank' style='color: #0092ff; text-decoration: underline;'><span style='color: #296068; font-family: Verdana, geneva, sans-serif; font-size: 19px;'><u>0478196080</u></span></a></p></div> </td> </tr></table></td> </tr></table></td> <td class='nl2go-responsive-hide' width='15' style='font-size: 0px; line-height: 1px;'>­ </td> </tr></table></th> </tr></table></td> </tr><tr class='nl2go-responsive-hide'><td height='10' style='font-size: 10px; line-height: 10px; background-color: #8eae58;'>­</td> </tr></table></td> </tr></table></td> </tr></table></td> </tr></table></body></html>
        ";

        $headers = array('Content-Type: text/html; charset=UTF-8');
        //var_dump(wp_mail($post_email, $subject, $text, $headers));
        wp_mail($post_email, $subject, $text, $headers);
    }

    function onMailError($wp_error)
    {
        var_dump($wp_error);
        // echo "<pre>";
        // print_r($wp_error);
        // echo "</pre>";
    }

    public function on_export($element) {}
}
