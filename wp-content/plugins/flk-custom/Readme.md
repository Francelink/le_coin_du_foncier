# Readme Module FLK 

## Structure
* assets/
    * css/
    * js/ 
    * scss/
* includes/
    * admin/
        * assets/
            * css/
            * js/ 
            * scss/
        * menu/
            * class-flk-admin-menu.php
        * class-flk-admin.php
    * class-flk-custom.php
* logs/
* templates/
    * views/
    * widgets/
        * shortcodes
* widgets/
    * shortcodes/
* flk-custom.php

## Creation d'une class
* Copier / coller le fichier 

```flk-custom/includes/class-flk-class-exemple.php```

    Le renommer en suivant la nomanclature suivante :
        - Nom du fichier : class-flk-nomdelaclass.php
        - La class : FLK_Nomdelaclass


* l'include dans le fichier 

```flk-custom/includes/class-flk-custom.php```
``` function includes()```

## Creation d'un shortcode
* Créer deux fichiers : 
    - Le fichier php class-flk-NomDuShortcode.php dans widgets/shortcodes/ se referer au fichier 
    ```/widgets/shortcodes/class-flk-shortcode-exemple.php```
    - Le fichier twig flk-NomDuShortcode.twig dans templates/widgets/shortcodes/ ( ce fichier servira de template )
* Modifier le fichier class-flk-shortcodes.php
    - Créer une fonction 
    ```public function NomDuShortcode()``` 
    et se referer à la function commentée 
    ```public function Shortcode_Exemple()```
    - Dans la function FLK_Shortcodes::include() ajouter la ligne 
    ```include_once FLK_PLUGIN_DIR  . '/widgets/shortcodes/class-flk-NomDuShortcode.php';``` 
    - Dans FLK_Shortcodes::init() dans l'array $shortcodes ajouter la ligne 
    ```'flk_NomDuShortcode'  => __CLASS__ . '::NomDuShortcode'```

## Creation d'un hook
Dans le fichier ```/flk-custom/includes/class-flk-hooks.php```

* Ajouter la fonction qui sera utilisé pour le hook 
``` @see public function flk_hook_exemple($arg) ```
* Ajouter ```add_action()``` ou ```add_filter()``` dans ```FLK_Hooks::__construct```

## Creation d'une metabox
Dans le fichier ```/includes/admin/class-flk-admin-metabox.php```

* Ajouter la fonction metabox() qui sera utilisé pour la metabox 
``` @see public function metabox_exemple() ```
* Ajouter la fonction add_flk_metabox 
 ```@see class FLK_Admin_Metabox::add_flk_metabox_exemple()```
 la lien de la doc est dispo en début du fichier 
* Ajouter l'action ```add_action('add_meta_boxes', array($this, 'add_flk_metabox'));```

## Gestion des fichiers twig et template 
Prérequis : Installer Timber via https://wordpress.org/plugins/timber-library/

Pour appeler une template 
```FLK_Render_template::renderTemplate('nom-du-ficheir-twig');```

## Gestion des logs
Dans le dossier logs/

* Créer le fichier .txt, s'il n'existe pas
* Pour écrire une ligne dans le fichier  ```FLK_Error::generateErrorFile('text', 'nom-du-fichier-log');``` 

## Appel à la BDD

Pour un appel à la bdd 
```FLK_Bdd_request::flk_query('requête', $array = true);``` 