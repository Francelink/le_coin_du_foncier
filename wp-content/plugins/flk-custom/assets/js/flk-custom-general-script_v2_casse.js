(function ($) {
    $(document).ready(function () { 
        
        // on gère l'ajout des shorcodes
        $('select[name="vigne_ou_cuvage"]').closest('.select').find(".select-options li").click(function (e) { 
            var val = $(this).attr('rel'); 
            if ( val === "cuvage" ) {
                $('#cuvage').find('.ajout_cuvage').each(function() {
                    $(this).show()
                })
                $('#parcelles').find('.ajout_parcelle').each(function() {
                    $(this).hide()
                })
            } else {
                $('#cuvage').find('.ajout_cuvage').each(function() {
                    $(this).hide()
                })
                $('#parcelles').find('.ajout_parcelle').each(function() {
                    $(this).show()
                })
            }
        });
        $('#parcelles').on('click', ".button_ajout_shortcode", function(event) {
                // on cache le block 
                toggle_bloc_parcelle_cuvage(event)
                // on ajoute un shorcode
                ajouter_shorcode($(event.target).closest('.button_ajout_shortcode'))
                //todo on envoie les données vers le formulaire de création d'article
        })
        $('#cuvage').on('click', ".button_ajout_shortcode", function(event) {
            // on cache le block 
            toggle_bloc_parcelle_cuvage(event)
            // on ajoute un shorcode
            ajouter_shorcode($(event.target).closest('.button_ajout_shortcode'))
            //todo on envoie les données vers le formulaire de création d'article
			
        })

        // on gère l'édition des parcelles et cuvage 
        $('#parcelles').on('click', ".edit", function(event) {
            // on affiche le block 
            toggle_bloc_parcelle_cuvage(event)
        })
        $('#cuvage').on('click', ".edit" , function(event) {
            // on affiche le block 
            toggle_bloc_parcelle_cuvage(event)
        })

        // on gère la suoprésion des parcelles et cuvage 
        $('#parcelles').on('click', ".supprimer", function(event) {
            // on affiche le block 
            remove_element(event)
        })
        $('#cuvage').on('click', ".supprimer" , function(event) {
            // on affiche le block 
            remove_element(event)
        })

        // on gère la prévalidation de l'offre              
        $("form.pre_validation input[type=submit]").click(function() {
            $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });
        $("form.pre_validation").on('submit', function(event) {
            event.preventDefault();
            // on gère les inputs required 
            var button = $("input[type=submit][clicked=true]").attr("data-admin");
            var required_validation = required_validation_function(event.target);
            if ( required_validation || button === "true") {
                // On récupère les données des goupes de champs et on crée les objets
                var inputs_post_offre  = {}
                var inputs_post_offre  = {}
                var inputs_votre_profil = [{}];
                var inputs_votre_offre = [{}];
                get_input_element(inputs_votre_profil,  $('.flk.votre_profil'));
                get_input_element(inputs_votre_offre,  $('.flk.votre_offre'))
                inputs_post_offre.votre_profil = inputs_votre_profil;
                inputs_post_offre.votre_offre = inputs_votre_offre;
                // on récupère le type d'offre 
                var field_id_select_type_offre = "field_62e10cf1103a2";
                if ( inputs_votre_offre[0][field_id_select_type_offre]["value"] === "vigne") { // on ajoute une parcelle
                    var type_offre = 11;
                    var inputs_parcelle = [];
                    var array = $('.flk.ajout_parcelle')
                    for (let index = 0; index < array.length; index++) {      
                        inputs_parcelle.push({})
                        $(array[index]).find('.sous_group').each(function() {
                            var sous_group_id = $(this).attr('data-key');
                            inputs_parcelle[index][sous_group_id] = [{}]
                            get_input_element(inputs_parcelle[index][sous_group_id],  $(this));
                        })
                    }
                    var insert_id_offre = create_post_offre(inputs_post_offre, type_offre, inputs_parcelle);
                } else { // on ajout un cuvage
                    var type_offre = 12;
                    var inputs_cuvage = [];
                    var array = $('.flk.ajout_cuvage')
                    for (let index = 0; index < array.length; index++) {      
                        inputs_cuvage.push({})
                        $(array[index]).find('.sous_group').each(function() {
                            var sous_group_id = $(this).attr('data-key');
                            inputs_cuvage[index][sous_group_id] = [{}]
                            get_input_element(inputs_cuvage[index][sous_group_id],  $(this));
                        })
                    }
                    var insert_id_offre = create_post_offre(inputs_post_offre, type_offre, inputs_cuvage);
                }                
            }
        });
        // on gère l'appel au Géoportal
        $("#parcelles").on('click', ".getGEOJSON", function (event) {
            event.preventDefault();
            var url =  "https://apicarto.ign.fr/api/cadastre/parcelle";
            var code_insee =  $(event.target).closest('.ajouter_une_parcelle').find('select[name="code_insee"]').val();
            var section = $(event.target).closest('.ajouter_une_parcelle').find('input[name="section"]').val();
            var numero = $(event.target).closest('.ajouter_une_parcelle').find('input[name="numero_parcelle"]').val();
            var settings = {
              url:
                url +
                "?code_insee=" +
                code_insee +
                "&section=" +
                section +
                "&numero=" +
                numero,
              method: "GET",
              timeout: 0,
            };
            $.ajax(settings).done(function (response) {
              var coordonnee = response.features[0].geometry.coordinates[0][0];
              var features = turf.points(coordonnee);
              var center = turf.center(features);
              var surface_m2 = response.features[0].properties.contenance;
              var surface_ha = parseFloat(surface_m2) / 10000;
              console.log(response, center, coordonnee, features);
              $(event.target).closest('.ajouter_une_parcelle').find('input[name="coordonees_gps"]').val(center.geometry.coordinates.join(';'))
              $(event.target).closest('.ajouter_une_parcelle').find('input[name="surface"]').val(surface_ha + ' ha') // todo convertir en hectare
            });
        });

        /// on gère la validation du formulaire
        $("form.validation").on('submit', function (e) {
            e.preventDefault();
            var cases = [{}];
            get_input_element(cases,  $('.flk.formulaire_validation'));
            debugger;
            var parent = $(e.target).find('input[name="parent_id"]').val();
            var enfants = [{}];
            $(e.target).find('input.enfant').each(function (indexInArray, valueOfElement) { 
                enfants[indexInArray] = $(this).val(); 
            });
            publish_post(parent, enfants) 
        });

        if ( $('body').hasClass('flk_categorie_offre') ) {
            var map;
            var elements = [];
            var lat = 45.991471;
            var lon = 4.718821;
            var macarte = null;
            //var map = init_carte(lat, lon, macarte);
            
            var map = L.map("flk_map").setView([lat, lon], 15);
            // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
            // Map par défaut : L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {minZoom: 1,maxZoom: 20,}).addTo(map);
            L.tileLayer
              .chinaProvider("Google.Satellite.Annotion", {
                // Il est toujours bien de laisser le lien vers la source des données
                minZoom: 10,
                maxZoom: 18,
              })
              .addTo(map);

            if ( $('body').hasClass("flk_categorie_offre-vigne") ) {
                $('td.field_62ebb6d713631').each(function(i){
                    var coordonnee = $(this).attr('data-value');
                    var pop_content = null;
                    elements[i] = {
                        "lon" : coordonnee.split(';')[0], 
                        "lat" : coordonnee.split(';')[1], 
                        "pop_content" : pop_content,
                    }
                })
            } else {
                $('input[name="post_id"]').each(function(i){
                    var coordonnee = $(this).val();
                    if ( coordonnee ) {
                        var pop_content = null;
                        elements[i] = {
                            "lon" : coordonnee.split(';')[1], 
                            "lat" : coordonnee.split(';')[0], 
                            "pop_content" : pop_content,
                        }
                    }
                })
            }


            add_marker(map, elements);
        }

        if ( $('body').hasClass("page-id-37") ) {
            // on init la map 
            var map 

            $('#flk_map').ready(function (e) {  
                var lat = 45.991471;
                var lon = 4.718821;
                var macarte = null;
                map = init_carte(lat, lon, macarte);
            })

            // on gère la recherche d'offre 
            $('a.button_recherche_shortcode').on('click', function (e) {
                e.preventDefault();
                // on gère les filtres 
                var data = [{}];
                var parent = $(e.target).closest('.body_shortcode_recherche');
                var select_type = $(e.target).closest('.body_shortcode_recherche').find('select#field_62f26a9ce00cd');
                var type_doffre = $(select_type).val();
                if ( type_doffre === "14" ) { // on cherche une parcelle 
                    $('select#field_62f26a6ce00cc').removeClass('flk_lieu');
                    $('select#field_62f26a6ce00cc').addClass('flk_ajouter_une_parcelle');
                } else if ( type_doffre === "13" ) { // on cherche un cuvage
                    $('select#field_62f26a6ce00cc').removeClass('flk_ajouter_une_parcelle');
                    $('select#field_62f26a6ce00cc').addClass('flk_lieu');
                } else {
                    // 
                }
                var selects = $(e.target).closest('.body_shortcode_recherche').find('select');
                var filtres = init_filtres(selects);
                console.log(filtres);
                data[0]["filtres"] = filtres;
                search_post(data, map);
            });
        }


        function get_input_element(array, groupe) {
            $(groupe).find('input').each(function() {
                var id = $(this).attr('id');
                if ( $(this).attr('type') === "radio") {
                    if( $(this).is(':checked') ){
                        array[0][id] = {
                            name : $(this).attr('name').trim(),
                            value : $(this).val()
                        }
                    }
                } else {
                    array[0][id] = {
                        name : $(this).attr('name').trim(),
                        value : $(this).val()
                    }
                }
            })
            $(groupe).find('select').each(function() {
                var id = $(this).attr('id');
                array[0][id] = {
                    name : $(this).attr('name').trim(),
                    value : $(this).val()
                }
            }) 
        }
        function print_shorcode(shorcode, element, target) {
           $.ajax({
               type: 'POST',
               url : flk_ajax_object.ajax_url,
               cache: false,
               data : { 'action': 'printShorcode', 'shorcode': shorcode, },
               complete : function() {  },
               success: function(response) {
                    var shorcode_html = $(response);
                    $( element ).after( $(response) );
                    $(target).attr('data-new', false);
               }
           });
        }
        function create_post_offre(inputs_parent, type_offre, inputs_enfant) {
            $.ajax({
                type: 'POST',
                url : '/wp-json/flk_api/v1/createPost',
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: JSON.stringify({inputs_parent: inputs_parent, type_offre: type_offre, inputs_enfant: inputs_enfant}),
                complete : function() {  },
                success: function(response) {
                    $(location).attr('href', '/?p=' + response.id_parent_offre );
                    // $("#loading-img").hide();
                    // $("#join-class-div-3").html(data);
                }
            });
        }
        function publish_post(parent, enfants) {
            $.ajax({
                type: 'POST',
                url : '/wp-json/flk_api/v1/publishPost',
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: JSON.stringify({parent: parent, enfants: enfants}),
                complete : function() {  },
                success: function(response) {
                    alert('Offre numéro ' + response.parent_id + 'publié')
                    // todo quoi faire après validation
                    // $("#loading-img").hide();
                    // $("#join-class-div-3").html(data);
                }
            });
        }
        function init_filtres(selects) {
            // Il y a trois cas de figure
            // --- Les relations AND si tous les selects n'ont pas de classe both 
            // --- Les relations OR si tous les selects ont both
            // --- Les relations AND et OR si tous les selects on les classes both et non 
            var filtres = [];
            var j = 0;
            var type = 0;
            $.each(selects, function (indexInArray, valueOfElement) { 
                // on récupère les classes pour select piur avoir le group 
                var select = $(this);
                var classListe = this.classList; 
                if ( $(this).val() !== "_") {
                    $.each(classListe, function (i, v) { 
                        if (v.includes("flk_")) {
                            var filtre = v.replace("flk_", '');
                            if (filtre !== "type") {
                                filtres[j] = {
                                    "name" : filtre + "_"+ $(select).attr('name'),
                                    "value" : $(select).val(),
                                }
                                j++;
                            } else{
                                type = $(select).val();
                                // relation AND
                            }
                        } 
                    })
                }
            });
            // on envoie le type de filtre soit uniquement parcelle, soit uniquement cuvage, soit les deux 
            console.log(type);
            if (type === 0) {
                var relation = "OR"; 
            } else {
                var relation = "AND";
            }
            return {
                "relation" : relation,
                "type" : type,
                "filtres" :  filtres
            }
        }
        function search_post(data, map) {
            $.ajax({
                type: 'POST',
                url : '/wp-json/flk_api/v1/searchPost',
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: JSON.stringify({data: data}),
                complete : function() {  },
                success: function(response) {
                    var elements = [];
                    $.each(response.results, function (i, v) {
                        if ( this.category_id === 14 ) { // vigne
                            var myIcon = L.icon({
                                iconUrl: '../wp-content/plugins/flk-custom/assets/images/pin_bleu.png',
                            });   
                            var pop_content = '<div class="popup_marker ' + this.titre + '">' 
                            + '<p class="titre"> ' 
                            + this.titre + '</p>' 
                            + '<p> Offre numéro : ' 
                            + this.Offre_titre + '</p>' 
                            + '<p> Commune : ' 
                            + this.Commune_name + '</p>' 
                            + '<p> Cépage : ' 
                            + this.Cepage + '</p>' 
                            + '<p> Cession : ' 
                            + this.Cession + '</p>' 
                            + '<a href="' + this.Offre_url + '" target="_blank" > VOIR L\'OFFRE</a>' 
                            '</div>';                     
                            var lon = v.Coordonees_gps.split(';')[0];
                            var lat = v.Coordonees_gps.split(';')[1];
                        } else if ( this.category_id === 13 ) { // cuvage
                            var myIcon = L.icon({
                                iconUrl: '../wp-content/plugins/flk-custom/assets/images/pin_vert.png',
                            });
                            var pop_content = '<div class="popup_marker ' + this.titre + '">' 
                            + '<p class="titre"> ' 
                            + this.titre + '</p>' 
                            + '<p> Offre numéro : ' 
                            + this.Offre_titre + '</p>' 
                            + '<p> Commune : ' 
                            + this.Commune_name + '</p>' 
                            + '<a href="' + this.Offre_url + '" target="_blank" > VOIR L\'OFFRE</a>' 
                            '</div>';      
                            var lon = v.Coordonees_gps.split(';')[1];
                            var lat = v.Coordonees_gps.split(';')[0];        
                        }

                        elements[i] = {
                            "lon" : lon, 
                            "lat" : lat, 
                            "pop_content" : pop_content,
                            "icon" : myIcon
                        }
                    });
                    add_marker(map, elements);
                    console.log(response.results);
                    // alert('Offre numéro ' + response.parent_id + 'publié')
                }
            });
        }
        function ajouter_shorcode(element) {
            var shorcode = $(element).attr('data-shorcode');
            var element_parent = $(element).closest(".flk");
            var new_element = $(element).attr('data-new');
            if (shorcode && new_element === "true" ) {
                print_shorcode(shorcode, element_parent, $(element));
            }
        }
        function toggle_bloc_parcelle_cuvage(event){
            $(event.target).closest(".flk").find(".body_shortcode_block").toggleClass('block_close');
            $(event.target).closest(".flk").find(".header_shortcode_block").toggleClass('button_hide');
        }
        function remove_element(event) {
            $(event.target).closest(".flk").remove();
            // todo message de confirmation
        }
        function required_validation_function(event){
            var required_validation = false;
            $('.flk').find('input').each(function(){
                if($(this).prop('required')){ // is required
                    // on vérifie si une valeur est entrée 
                    if ( $(this).val() === '' ) {
                        if ( $(this).next('span.flk_erreur').length === 0 ) {
                            var message_erreur = '<span class="flk_erreur"> Merci de completer ce champ </span>';
                            $(message_erreur).insertAfter($(this));
                        }
                        $(this).addClass('error');
                        required_validation = false;
                    } else {
                        $(this).next('span.flk_erreur').remove();
                        $(this).removeClass('error');
                        required_validation = true;
                    }
                }
            })
            $('.flk').find('select').each(function(){
                if($(this).prop('required')){ // is required
                    // on vérifie si une valeur est entrée 
                    if ( $(this).val() === '_' ) {
                        if ( $(this).next('span.flk_erreur').length === 0 ) {
                            var message_erreur = '<span class="flk_erreur"> Merci de completer ce champ </span>';
                            $(message_erreur).insertAfter($(this));
                        }
                        $(this).addClass('error');
                        required_validation = false;
                    } else {
                        $(this).next('span.flk_erreur').remove();
                        $(this).removeClass('error');
                        required_validation = true;
                    }
                }
            })
            $('.flk').find('.fieldset_required.fieldset_radio').each(function(){ // input radio 
                var all_checked = false;
                $(this).find('input').each(function(){
                    if( $(this).is(':checked') ){
                        all_checked = true
                    }
                })
                // on vérifie si tout les radios sont check 
                if ( all_checked === false ) {
                    if ( $(this).find('span.flk_erreur').length === 0 ) {
                        var message_erreur = '<span class="flk_erreur"> Merci de choisir une option </span>';
                        $(this).append($(message_erreur));
                    }
                    $(this).addClass('error');
                    required_validation = false;
                } else {
                    $(this).find('span.flk_erreur').remove();
                    $(this).removeClass('error');
                    required_validation = true;
                }
            })
            
            return required_validation;
        }

        function init_carte(lat, lon, map) {  // Fonction d'initialisation de la carte // init la carte sans points 
            // On initialise la latitude et la longitude de Paris (centre de la carte)
            // Créer l'objet "map" et l'insèrer dans l'élément HTML qui a l'ID "flk_map"
            map = L.map('flk_map').setView([lat, lon], 7);
            // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
            L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                // Il est toujours bien de laisser le lien vers la source des données
                attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            }).addTo(map);
            return map
        }
        
        function add_marker(map, elements) {
            if ( elements.length > 0 ) {
                for (var i = 0; i < elements.length; i++) {
                    if ( elements[i].pop_content !== null) {
                        if ( elements[i].icon ) {
                            marker = new L.marker([elements[i].lat, elements[i].lon], {icon: elements[i].icon}).bindPopup(elements[i].pop_content).addTo(map);
                        } else {
                            marker = new L.marker([elements[i].lat, elements[i].lon]).bindPopup(elements[i].pop_content).addTo(map);
                        }
                    } else {
                        marker = new L.marker([elements[i].lat, elements[i].lon]).addTo(map);
                    }
                }
            } 
        }
    

    //    $('.button_ajout_shortcode').on('click', function(event) {
    //         // on cache ou show le block 
    //         $(event.target).closest(".body_shortcode_block").toggleClass('block_close');
    //         $(event.target).closest(".flk").find(".header_shortcode_block").toggleClass('button_hide');
    //    })
    });
})(jQuery);