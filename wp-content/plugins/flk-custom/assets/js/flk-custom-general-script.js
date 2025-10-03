(function ($) {
  $(document).ready(function () {

    $("form.validation input[value='PUBLIER L’OFFRE']").prop( "disabled", true );

    /*let isChecked_CGU_MENTION = $('#CGU-MENTION')[0].checked
    let isChecked_SAFER = $('#SAFER')[0].checked
    let isChecked_CHAMBRE = $('#CHAMBRE')[0].checked*/

    /*$('form.validation input').change(function() {
      alert('Checkbox checked!');
    });*/
    $('div.buttons_conditions').find('input').on('click', function() {
      if($("#cgu_conditions").is(':checked')){
        if($("#safer_conditions" ).is(':checked')){
            $("form.validation input[value='PUBLIER L’OFFRE']").prop( "disabled", false );
        }
        else{
          $("form.validation input[value='PUBLIER L’OFFRE']").prop( "disabled", true );
        }
      }else{
        $("form.validation input[value='PUBLIER L’OFFRE']").prop( "disabled", true );
      }
    });

    $('input').each(function() {
      var inputValue = $(this).val();
      if(inputValue != "") {
        $(this).parent().addClass('focused');  
      } else {
        $(this).parent().removeClass('focused');  
      }
    });

    /*$('.button_ajout_shortcode').on('click', function(e){
      e.preventDefault();
      var elem = $('.h2_votre_offre');
      if( elem.length ){
        var elemTop = elem.first().offset().top;
        $('html,body').animate({
          scrollTop:elemTop-50,
        }, "fast");
      }
    });*/
    $(document).on('click', '.button_ajout_shortcode', function(e){
      e.preventDefault();
      var elem = $('p.ajout');
      if( elem.length ){
        var elemTop = elem.first().offset().top;
        $('html,body').animate({
          scrollTop:elemTop-50,
        }, "fast");
      }
    });

  

    // nombre entier capaciter
    jQuery(document).on("input", "#field_62ebbdb871424", function() {
      this.value = this.value.replace(/\D/g,'');
    });


    // nombre entier surface
    jQuery(document).on("input", "#field_62ebbdae71423", function() {
      this.value = this.value.replace(/\D/g,'');
    });

    // si retour à l'offre pour la modifier on ajoute les shortcodes 
    if ( $("body").hasClass("page-id-34") ) {
      
        var val = $('input[name="sessionElementCat"]');
        $('input[name="sessionElementEnfant"]').each(function(e){     
            if (val === "11") {
              // On va supprimer le bloc non souhaité au changement de choix entre Parcelle ou Cuvage, ici la parcelle car on souhaite ajouter un cuvage
              remove_shortcode($(".ajout_parcelle"));
              print_shorcode("flk_cuvage", null, $(this).val());
              $('.pre_validation').hide();
            } else {
              // On va supprimer le bloc non souhaité au changement de choix entre Parcelle ou Cuvage, ici le cuvage car on souhaite ajouter une parcelle
              remove_shortcode($(".ajout_cuvage"));
              print_shorcode("flk_parcelle", null, $(this).val());
              $('.pre_validation').hide();
            }
        })
      
    }



    // on gère l'ajout des shorcodes
    $('select[name="vigne_ou_cuvage"]')
      .closest(".select")
      .find(".select-options li")
      .click(function (e) {
        var val = $(this).attr("rel");
        if (val === "cuvage") {
          // On va supprimer le bloc non souhaité au changement de choix entre Parcelle ou Cuvage, ici la parcelle car on souhaite ajouter un cuvage
          remove_shortcode($(".ajout_parcelle"));
          print_shorcode("flk_cuvage", null);
          $('.pre_validation').hide();
        } else {
          // On va supprimer le bloc non souhaité au changement de choix entre Parcelle ou Cuvage, ici le cuvage car on souhaite ajouter une parcelle
          remove_shortcode($(".ajout_cuvage"));
          print_shorcode("flk_parcelle", null);
          $('.pre_validation').hide();
        }

        // $('select[name="vigne_ou_cuvage"]')
        //   .closest(".select")
        //   .css({ cursor: "not-allowed" });
        // $('select[name="vigne_ou_cuvage"]')
        //   .closest(".select")
        //   .find(".select-styled")
        //   .css({ "pointer-events": "none" });
      });

    // on gère l'ajout des shorcodes
    $("#parcelles").on("click", ".button_valider_parcelle", function (event) {
      // on cache le block
      toggle_bloc_parcelle_cuvage(event);
      // On passe l'attribut "data-new" à false pour forcer le label du bouton à être "Modifier l'offre"
      $(event.target).closest(".button_ajout_parcelle").attr("data-new", false);
    });

    // on gère l'ajout des shorcodes
    $("#parcelles").on("click", ".button_ajout_shortcode", function (event) {
      // on ajoute un shorcode
      ajouter_shorcode($(event.target).closest(".button_ajout_shortcode"));
      //todo on envoie les données vers le formulaire de création d'article
    });
    $("#cuvage").on("click", ".button_ajout_shortcode", function (event) {
      // on ajoute un shorcode
      ajouter_shorcode($(event.target).closest(".button_ajout_shortcode"));
      toggle_bloc_parcelle_cuvage(event);
      //todo on envoie les données vers le formulaire de création d'article

      // On passe l'attribut "data-new" à false pour forcer le label du bouton à être "Modifier l'offre"
      $(event.target).closest(".button_ajout_cuvage").attr("data-new", false);
    });

    // on gère l'édition des parcelles et cuvage
    $("#parcelles").on("click", ".edit", function (event) {
      // on affiche le block
      toggle_bloc_parcelle_cuvage(event);
      $('.pre_validation').hide();
      //$('.bloc_edition_parcelle').removeClass('block_close');
    });
    $("#cuvage").on("click", ".edit", function (event) {
      // on affiche le block
      toggle_bloc_parcelle_cuvage(event);
      $('.pre_validation').hide();
    });

    // on gère la suoprésion des parcelles et cuvage
    $("#parcelles").on("click", ".supprimer", function (event) {
      // on affiche le block
      remove_element(event);
    });
    $("#cuvage").on("click", ".supprimer", function (event) {
      // on affiche le block
      remove_element(event);
      // if ($("#cuvage").find(".flk.ajout_cuvage").length === 0) {
      //   $('select[name="vigne_ou_cuvage"]')
      //     .closest(".select")
      //     .css({ cursor: "pointer" });
      //   $('select[name="vigne_ou_cuvage"]')
      //     .closest(".select")
      //     .find(".select-styled")
      //     .css({ "pointer-events": "auto" });
      // }
    });

    // on gère la prévalidation de l'offre
    /*$("form.pre_validation input[type=submit]").click(function () {
      $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
      $(this).attr("clicked", "true");
    });*/

    //$("form.pre_validation input[type=submit]").prop("disabled", true);

    // on gère la prévalidation de l'offre
    $("form.pre_validation input[type=submit]").click(function () {
      $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
      $(this).attr("clicked", "true");
     
    });

    $("form.pre_validation").on("submit", function (event) {
      event.preventDefault();
      // on gère les inputs required
      var button = $("input[type=submit][clicked=true]").attr("data-admin");
      var required_validation = required_validation_function(event.target);
      console.log(required_validation)
      
      if (required_validation || button === "true") {
        // On récupère les données des goupes de champs et on crée les objets
        var inputs_post_offre = {};
        var inputs_post_offre = {};
        var inputs_votre_profil = [{}];
        var inputs_votre_offre = [{}];
        get_input_element(inputs_votre_profil, $(".flk.votre_profil"));
        get_input_element(inputs_votre_offre, $(".flk.votre_offre"));
        inputs_post_offre.votre_profil = inputs_votre_profil;
        inputs_post_offre.votre_offre = inputs_votre_offre;
        // on récupère le type d'offre
        var field_id_select_type_offre = "field_62e10cf1103a2";
        if (
          inputs_votre_offre[0][field_id_select_type_offre]["value"] === "vigne"
        ) {
          // on ajoute une parcelle
          var type_offre = 11;
          var inputs_parcelle = [];
          var array = $(".flk.ajout_parcelle");
          for (let index = 0; index < array.length; index++) {
            inputs_parcelle.push({});
            $(array[index])
              .find(".sous_group")
              .each(function () {
                var sous_group_id = $(this).attr("data-key");
                inputs_parcelle[index][sous_group_id] = [{}];
                get_input_element(
                  inputs_parcelle[index][sous_group_id],
                  $(this)
                );
              });
          }
                    
          var insert_id_offre = create_post_offre(
            inputs_post_offre,
            type_offre,
            inputs_parcelle
          );
          // Cookies.set('id_offre',  insert_id_offre );
          // var cookie = $.cookie('id_offre');
          // console.log(cookie);
        } else {
          // on ajout un cuvage
          var type_offre = 12;
          var inputs_cuvage = [];
          var array = $(".flk.ajout_cuvage");
          for (let index = 0; index < array.length; index++) {
            inputs_cuvage.push({});
            $(array[index])
              .find(".sous_group")
              .each(function () {
                var sous_group_id = $(this).attr("data-key");
                inputs_cuvage[index][sous_group_id] = [{}];
                get_input_element(inputs_cuvage[index][sous_group_id], $(this));
              });
          }
          //debugger
          var insert_id_offre = create_post_offre(
            inputs_post_offre,
            type_offre,
            inputs_cuvage
          );
        }
      }
    });

    // on gère l'appel au Géoportal
    $("#parcelles").on("click", ".getGEOJSON", function (event) {
      event.preventDefault();
      $('.flk_erreur.geo').remove();

      var section = $('#field_62eb9f240fb78').val(); /* geo */
		  var parcelle = $('#field_62eb9f430fb79').val();

      //console.log(section, parcelle);
      if(parcelle.length === 4){
        $('.fieldset_numero_parcelle .flk_erreur.geo').remove();
			}else{ 
         $('.fieldset_numero_parcelle .instructions').after('<span class="flk_erreur geo">Le champ doit contenir <br>4 caractères minimums</span>');
			}
			// surface
			if(section.length === 2){
				$('.fieldset_section .flk_erreur.geo').remove();	  
			}else{
         $('.fieldset_section .instructions').after('<span class="flk_erreur geo">Le champ doit contenir <br>2 caractères minimums</span>');
			}
         // On gère l'affichage du spinner
          // Ici on l'affiche et on masque le texte "Recherche"
          if($('.loader_custom').length) {
            $('.loader_custom').show();
          }
          if($('.recherche_label_jecherche').length) {
            $('.recherche_label_jecherche').hide();
          }

      var url = "https://apicarto.ign.fr/api/cadastre/parcelle";
      var code_insee = $(event.target)
        .closest(".ajouter_une_parcelle")
        .find('select[name="code_insee"]')
        .val();
      var section = $(event.target)
        .closest(".ajouter_une_parcelle")
        .find('input[name="section"]')
        .val();
      var numero = $(event.target)
        .closest(".ajouter_une_parcelle")
        .find('input[name="numero_parcelle"]')
        .val();

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

        // On gère l'affichage du spinner
          // Ici on l'affiche et on masque le texte "Recherche"
          if($('.loader_custom').length) {
            $('.loader_custom').hide();
          }
          if($('.recherche_label_jecherche').length) {
            $('.recherche_label_jecherche').show();
          }
          
        // var coordonnee = response.features[0].geometry.coordinates[0][0];
        // var features = turf.points(coordonnee);
        // var center = turf.center(features);
        // var surface_m2 = response.features[0].properties.contenance;
        // var surface_ha = parseFloat(surface_m2) / 10000;
        //console.log(response, center, coordonnee, features);
        console.log(response);
        if(response.features.length === 0){
          // debugger
        var message_erreur = '<span class="flk_erreur gps-erreur"> Vérifier si le contenu de la section et du N° parcelle est exact. </span>';
          if($('.flk_erreur.gps-erreur').length) {
            $(".fieldset_coordonees_gps").remove($(message_erreur));
          }else{
            $(".fieldset_coordonees_gps").append($(message_erreur));
          }
        }else{
        var coordonnee = response.features[0].geometry.coordinates[0][0];
        var features = turf.points(coordonnee);
        var center = turf.center(features);
        var surface_m2 = response.features[0].properties.contenance;
        var surface_ha = parseFloat(surface_m2) / 10000;
        //console.log(response, center, coordonnee, features);
        $(event.target)
          .closest(".ajouter_une_parcelle")
          .find('input[name="coordonees_gps"]')
          .val(center.geometry.coordinates.join(";"));
        $(event.target)
          .closest(".ajouter_une_parcelle")
          .find('input[name="surface"]')
          .val(surface_ha + " ha"); // todo convertir en hectare

          // TODO Vérifier que l'input coordonnees gps a du contenu,
          // Si oui, on ajoute la classe "focused" sur le fieldset
          // Si non, bah on fait rien
          if($(event.target).closest(".ajouter_une_parcelle").find('input[name="coordonees_gps"]').val() != ""){
            $( "fieldset.fieldset_coordonees_gps" ).addClass( "focused" );
            if($('.flk_erreur.gps').length) {
              $('.flk_erreur.gps').hide();
            }
            if($('.flk_erreur.gps-erreur').length) {
              $('.flk_erreur.gps-erreur').hide();
            }
          }
        }
          // TODO Vérifier que l'input surface a du contenu
          // Si oui, on ajoute la classe "focused" sur le fieldset
          // Si non, bah on fait rien
          if($(event.target).closest(".ajouter_une_parcelle").find('input[name="surface"]').val() != ""){
            $( "fieldset.fieldset_surface" ).addClass( "focused" );
          }else{
            //
          }

          // console.log(response);
      }).fail(function(data, status) {
        // alert(status);
        // message erreur si probleme 
        var message_erreur = '<span class="flk_erreur gps"> Ces informations ne permettent pas de localiser les coordonnées GPS</span><br>';
        if($('.flk_erreur').length) {
          $(".fieldset_coordonees_gps").remove($(message_erreur));
        }else{
          $(".fieldset_coordonees_gps").append($(message_erreur));
        }

        
        //loader 
        if($('.loader_custom').length) {
          $('.loader_custom').hide();
        }
        if($('.recherche_label_jecherche').length) {
          $('.recherche_label_jecherche').show();
        }
      });
    });

    /// on gère la validation du formulaire
    $("form.validation").on("submit", function (e) {
      e.preventDefault();
      var cases = [{}];
      get_input_element(cases,  $('.flk.formulaire_validation .groupe_radio'));
      var parent = $(e.target).find('input[name="parent_id"]').val();
      var enfants = [{}];
      $(e.target)
        .find("input.enfant")
        .each(function (indexInArray, valueOfElement) {
          enfants[indexInArray] = $(this).val();
        });
      publish_post(parent, enfants, cases);
    });

    if ($("body").hasClass("flk_categorie_offre")) {
      var map;
      var elements = [];
      var lat = 45.991471;
      var lon = 4.718821;
      var macarte = null;
      var map = init_carte(lat, lon, macarte);
      var coordonneesgps_result = null;

      $.ajax({
        type: "POST",
        url: "/wp-json/flk_api/v1/searchPost",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        //data: JSON.stringify({ data: data }),
        complete: function () {},
        success: function (response) { 
          $.each(response.results, function (i, v, e) {
             //console.log(v);
             var id_offre = $('input[name="parent_id"]').val();
             if(id_offre === v.Offre_id){
              coordonneesgps_result = v.Coordonees_gps;
              console.log(coordonneesgps_result)
             return false;
             }/*else{
              return null;
             }*/
          });

      /*var map = L.map("flk_map").setView([lat, lon], 15);
      // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
      // Map par défaut : L.tileLayer("https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png", {minZoom: 1,maxZoom: 20,}).addTo(map);
      L.tileLayer
        .chinaProvider("Google.Satellite.Annotion", {
          // Il est toujours bien de laisser le lien vers la source des données
          //minZoom: 10,
          maxZoom: 18,
        })
        .addTo(map);*/

      if ($("body").hasClass("flk_categorie_offre-vigne")) {
        // vigne
        var myIcon = L.icon({
          iconUrl:
            "../wp-content/plugins/flk-custom/assets/images/pin_bleu.png",
          iconSize: [29, 43],
          iconAnchor: [14, 43],
        });
        $("td.field_62ebb6d713631").each(function (i) {
          var coordonnee = $(this).html();
          var pop_content = "";
          elements[i] = {
            lon: coordonnee.split(";")[0],
            lat: coordonnee.split(";")[1],
            icon: myIcon,
            pop_content: pop_content,
          };

          console.log(elements[i]);
        });
      } else if($("body").hasClass("flk_categorie_offre-cuvage")) {
        // cuvage
        var myIcon = L.icon({
          iconUrl:
            "../wp-content/plugins/flk-custom/assets/images/pin_vert.png",
          iconSize: [29, 43],
          iconAnchor: [14, 43],
        });
        $('input[name="coordonnees"]').each(function (i) {
          var coordonnee_commune = $(this).val();
          var coordonnee_gps = coordonneesgps_result;
         // console.log(coordonnee);
          if (coordonnee_gps !== '') {
            var coordonnee = coordonnee_gps;
            console.log(coordonnee);
            var pop_content = null;
            elements[i] = {
              lon: coordonnee.split(";")[1],
              lat: coordonnee.split(";")[0],
              icon: myIcon,
              pop_content: pop_content,
            };
          }else{
            var coordonnee = coordonnee_commune;
            console.log(coordonnee_commune);
            var pop_content = null;
            elements[i] = {
              lon: coordonnee.split(";")[1],
              lat: coordonnee.split(";")[0],
              icon: myIcon,
              pop_content: pop_content,
            };
          }
        });
      }
      

      add_marker(map, elements);
    }
       });
    }
    

    if ($("body").hasClass("page-id-37")) {
      // on init la map
      var map;
      var marker;
      var markerArray = [];
      $("#flk_map").ready(function (e) {
        var lat = 45.991471;
        var lon = 4.718821;
        var macarte = null;
        map = init_carte(lat, lon, macarte);

        //var data = {relation: 'OR'};
        var data = { relation: "AND" };
        console.log(markerArray);
        markerArray = search_post(data, map, markerArray);
        console.log(data);

        $('#reinit_filtres').hide();
        //add_marker(map, elements);
      });
      // on gère la recherche d'offre
      $("a.button_recherche_shortcode").on("click", function (e) {
        e.preventDefault();

        // On gère l'affichage du spinner
        // Ici on l'affiche et on masque le texte "Recherche"
        if($('.loader_custom').length) {
          $('.loader_custom').show();
        }
        if($('.recherche_label_jecherche').length) {
          $('.recherche_label_jecherche').hide();
        }

        // on gère les filtres
        var data = [{}];
        var parent = $(e.target).closest(".body_shortcode_recherche");
        var select_type = $(e.target)
          .closest(".body_shortcode_recherche")
          .find("select#field_62f26a9ce00cd");
        var type_doffre = $(select_type).val();
        console.log(type_doffre);
        if (type_doffre === "14") {
          // on cherche une parcelle
          $("select#field_62f26a6ce00cc").removeClass("flk_lieu");
          $("select#field_62f26a6ce00cc").removeClass("flk_ajouter_une_offre_complete");
          $("select#field_62f26a6ce00cc").addClass("flk_ajouter_une_parcelle");
          $("select#field_62f26bc60c77b").removeClass("flk_complement");
          $("select#field_62f26bc60c77b").addClass("flk_complements");
        } else if (type_doffre === "13") {
          // on cherche un cuvage
          $("select#field_62f26a6ce00cc").removeClass(
            "flk_ajouter_une_offre_complete"
          );
          $("select#field_62f26a6ce00cc").removeClass(
            "flk_ajouter_une_parcelle"
          );
          $("select#field_62f26a6ce00cc").addClass("flk_lieu");
          $("select#field_62f26bc60c77b").addClass("flk_complement");
          $("select#field_62f26bc60c77b").removeClass("flk_complements");
        } else if (type_doffre === "20") {
          // on cherche une offre complete
          $("select#field_62f26a6ce00cc").removeClass(
            "flk_ajouter_une_parcelle"
          );
          $("select#field_62f26a6ce00cc").removeClass(
            "flk_lieu"
          );
          $("select#field_62f26a6ce00cc").addClass("flk_ajouter_une_offre_complete");
        }
        else {
          //
          $("select#field_62f26a6ce00cc").addClass("flk_ajouter_une_parcelle flk_ajouter_une_offre_complete flk_lieu");
        }

        /*if ($('.leaflet-marker-pane').empty().delay(5000)) {
          $('.leaflet-map-pane').addClass('warning');
        }*/

        /*$("a.button_recherche_shortcode").on("click", function (e) {
            e.preventDefault();
          if($('.leaflet-marker-pane').html() == "") {
            $('.leaflet-map-pane').addClass('warning');
          }else
          {
            $('.leaflet-map-pane').removeClass('warning');
          }
        });*/

        var selects = $(e.target)
          .closest(".body_shortcode_recherche")
          .find("select");
        var filtres = init_filtres(selects);

        console.log(markerArray);
        data[0]["filtres"] = filtres;
        
        search_post(data, map, markerArray);

        $('#reinit_filtres').show();
      });
      // on gère la réinitialisation des filtres
      $("a.button_reinitialisation_filtres").on("click", function (e) {
        e.preventDefault();
        $('.fieldset_type_de_cession').show();
        $('.recherche_complement_cuvage').hide();
        $('.recherche_complement_vigne').hide();
        // on gère les filtres
        var data = [{}];

        if(!$("select#field_62f26a6ce00cc").hasClass('flk_lieu')) {
          $("select#field_62f26a6ce00cc").addClass("flk_lieu");
        }

        if(!$("select#field_62f26a6ce00cc").hasClass('flk_ajouter_une_parcelle')) {
          $("select#field_62f26a6ce00cc").addClass("flk_ajouter_une_parcelle");
        }

        if(!$("select#field_62f26a6ce00cc").hasClass('flk_ajouter_une_offre_complete')) {
          $("select#field_62f26a6ce00cc").addClass("flk_ajouter_une_offre_complete");
        }

        var selects = $(e.target)
          .closest(".body_shortcode_recherche")
          .find("select");
        var filtres = init_filtres(selects);

        console.log(markerArray);
        data[0]["filtres"] = filtres;
        
        reinit_post(data, map, markerArray);

        // On gère les select custom pour qu'ils prennent la valeur par défaut suite au reset
        $('select').each(function(e) {
          var $this = $(this), numberOfOptions = $(this).children('option').length;
          var $styledSelect = $this.next('div.select-styled');
		      $styledSelect.text($this.children('option').eq(0).text());
          $this.val('_');
        });
      });
      // on gère l'enregistrement des alertes en BDD
      $("form#alert_form").on("submit", function (e) {
        e.preventDefault();
        var commune = $(e.target).find('select[name="code_insee"]').val();
        var email = $(e.target).find('input[name="email"]').val();
        $.ajax({
          type: "POST",
          url: "/wp-json/flk_api/v1/AlerteInsert",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          data: JSON.stringify({
            commune: commune,
            email: email,
          }),
          complete: function () {},
          success: function (response) {
            if (response.response === "ok") {
              $("span.response").html("Votre inscription est enregistrée");
            }
          },
        });
      });
      // on affiche les sous groupe de filtres en function du type. 
      $('select#field_62f26a9ce00cd').change(function(e) {
        var value = $(this).val();
        switch (value) {
          case "13":
            $('.recherche_complement_cuvage').hide();
            $('.recherche_complement_vigne').hide();
            $('.recherche_complement_cuvage').show();
            $('.fieldset_type_de_cession').show();
            break;
          case "14":
            $('.recherche_complement_cuvage').hide();
            $('.recherche_complement_vigne').hide();
            $('.recherche_complement_vigne').show();
            $('.fieldset_type_de_cession').show();
            break;
          case "20":
            $('.recherche_complement_cuvage').hide();
            $('.recherche_complement_vigne').hide();
            $('.fieldset_type_de_cession').hide();
            break;
          default:
            $('.recherche_complement_cuvage').hide();
            $('.recherche_complement_vigne').hide();
            break;
        }
      }) 
    }

    function get_input_element(array, groupe) {
      $(groupe)
        .find("input")
        .each(function () {
          var id = $(this).attr("id");
          var type = $(this).attr("type"); 
          switch (type) {
            case "radio":
              if ($(this).is(":checked")) {
                array[0][id] = {
                  name: $(this).attr("name").trim(),
                  type: "radio",
                  value: $(this).val(),
                };
              }
              break;
            case "checkbox":
              if ($(this).is(":checked")) {
                var name = $(this).attr("name").trim();
                if ( array[0][name] === undefined  ) { // array n'existe pas on le crée 
                  array[0][name] = {
                    name: name,
                    type: "select",
                    value : [],
                  } 
                }
                array[0][name]["value"].push($(this).val())
              }
              break;
            default:
                array[0][id] = {
                  name: $(this).attr("name").trim(),
                  type: "autre",
                  value: $(this).val(),
                };
              break;
          }
        });
      $(groupe)
        .find("select")
        .each(function () {
          var id = $(this).attr("id");
          array[0][id] = {
            name: $(this).attr("name").trim(),
            type: "select_unique",
            value: $(this).val(),
          };
        });

        // on ajoute l'observation 
        if ( $(groupe).hasClass('votre_offre') ) {
          var id = "field_634d1fa3d3d7b";
          var obs = $('input#field_634d1fa3d3d7b');
          if ( obs ) {       
            array[0][id] = {
              name: $('input#field_634d1fa3d3d7b').attr("name").trim(),
              type: "autre",
              value: $('input#field_634d1fa3d3d7b').val(),
            };
          }
        }
    }
    function print_shorcode(shorcode, target, id = false) {
      $.ajax({
        type: "POST",
        url: flk_ajax_object.ajax_url,
        cache: false,
        data: { action: "printShorcode", shorcode: shorcode, id: id },
        complete: function () {},
        success: function (response) {
          var shorcode_html = $(response);
          //$( element ).after( $(response) );
          var lastChild;
          if (shorcode === "flk_cuvage") {
            var emplacement = $("#parcelles #shortcode-offre");
            emplacement.append($(response));
            lastChild = $("#parcelles .flk:last-child");

            reorganisation_cuvage(lastChild);

            /* Scroll automatiquement sur la partie souhaitée */
            $("html, body").animate(
              {
                scrollTop: lastChild.offset().top,
              },
              500
            );
          } else {
            var emplacement = $("#parcelles #shortcode-offre");
            emplacement.append($(response));
            lastChild = $("#parcelles .flk:last-child");

            reorganisation_parcelles(lastChild);

            /* Scroll automatiquement sur la partie souhaitée */
            $("html, body").animate(
              {
                scrollTop: lastChild.offset().top,
              },
              500
            );
          }
          if ($(target)) {
            $(target).attr("data-new", false);
          }
          // $("#loading-img").hide();
          // $("#join-class-div-3").html(data);
        },
      });
    }
    function create_post_offre(inputs_parent, type_offre, inputs_enfant) {
      $.ajax({
        type: "POST",
        url: "/wp-json/flk_api/v1/createPost",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({
          inputs_parent: inputs_parent,
          type_offre: type_offre,
          inputs_enfant: inputs_enfant,
        }),
        complete: function () {},
        success: function (response) {
          $(location).attr(
            "href",
            "/?p=" + response.id_parent_offre + "&key=foryoureyesonly"
          );
          // $("#loading-img").hide();
          // $("#join-class-div-3").html(data);
        },
      });
    }
    function publish_post(parent, enfants, cases) {
      $.ajax({
        type: "POST",
        url: "/wp-json/flk_api/v1/publishPost",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({ parent: parent, enfants: enfants, cases: cases }),
        complete: function () {},
        success: function (response) {
          //debugger
          //alert("Offre numéro " + response.parent_id + " publié");
          elementorProFrontend.modules.popup.showPopup( {id:2778}, event);  
          // todo quoi faire après validation
          // $("#loading-img").hide();
          // $("#join-class-div-3").html(data);
          //window.location.href="https://coindufoncier.francelink.fr/offre-n-" + response.parent_id ;
        },
      });
    }
    function init_filtres(selects) {
      // cas de figures 
      // ----> on récupère uniquement la ville : recherche dans vigne et cuves 
      // ----> on récupère uniquement type de cuves : recherche uniquement des cuves ( il faut lui balancer la catégorie cuves )
      // ----> on recupère uniquement nature de l'offre : recherche uniqement des parcelles ( il faut lui balancer la catégorie parcelles )
      // ----> on récupère la ville + nature de loffre : recherche uniqement des parcelles ( il faut lui balancer la catégorie parcelles )
      // ----> on récupère la ville + type de cuves  : recherche uniqement des cuves ( il faut lui balancer la catégorie cuves )
      var filtres = [];
      var liste_des_nom_filtre = [];
      var j = 0;
      var type = 0;
      $.each(selects, function (indexInArray, valueOfElement) {
        // on récupère les classes pour select piur avoir le group
        var select = $(this);
        var classListe = this.classList;
        if ($(this).val() !== "_") {
          $.each(classListe, function (i, v) {
            if (v.includes("flk_")) {
              var filtre = v.replace("flk_", "");
              if (filtre !== "type") {
                switch (filtre) {
                  case 'complement':
                    var name = filtre + "_" + $(select).attr("name") + "_cuvage"; 
                    break;
                  case 'complements':
                    var name = filtre + "_" + $(select).attr("name") + "_parcelle"; 
                    break;
                  default:
                    var name = filtre + "_" + $(select).attr("name");
                    break;
                }
                filtres[j] = {
                  name: name,
                  value: $(select).val(),
                };
                liste_des_nom_filtre[filtre] = filtre;
                console.log(liste_des_nom_filtre);
                j++;
              } else {
                type = $(select).val();
                // relation AND
              }
            }
          });
        }
      });
      // on envoie le type de filtre soit uniquement parcelle, soit uniquement cuvage, soit les deux
      console.log(type);
      if (type === 0) {
        var relation = "OR";
        //var relation = "AND";
      } else {
        var relation = "AND";
        // on garde qu'un seul champs complément 
        if (filtres.length === 2 ) {
          if ( type === "14" && filtres[0].name === "complement_type_de_cession_cuvage") {
            filtres.shift();
          } else if (type === "13" && filtres[1].name === "complements_type_de_cession_parcelle") {
            filtres.splice(1);
          }
        }
      }
      //
      var filtres_relation = "OR"; 
      // var filtres_relation = "AND"; 
      if ( liste_des_nom_filtre["lieu"] && liste_des_nom_filtre["complements"]) {
        var filtres_relation = "AND";
        var filtres_cuve = [];
        var filtres_parcelle = [];
        var w = 0
        filtres.forEach(element => {
          switch (element.name) {
            case "lieu_code_insee":
              filtres_cuve.push(element);
              w++
              break;
            case "ajouter_une_parcelle_code_insee":
              filtres_parcelle.push(element);
              w++
              break;
            case "complement_type_de_cession_cuvage":
              filtres_cuve.push(element);
              w++
              break;
            case "complements_type_de_cession_parcelle":
              filtres_parcelle.push(element);
              w++
              break;    
          }
        });
        var filtres = [filtres_cuve, filtres_parcelle];
      }
      return {
        relation: relation,
        type: type,
        filtres: filtres,
        filtres_relation : filtres_relation,// c'est la relation inter filtre 
      };
    }
    function search_post(data, map, markerArray) {
      console.log(data);
      $.ajax({
        type: "POST",
        url: "/wp-json/flk_api/v1/searchPost",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({ data: data }),
        complete: function () {},
        success: function (response) {          

          // On gère l'affichage du spinner
          // Ici on l'affiche et on masque le texte "Recherche"
          if($('.loader_custom').length) {
            $('.loader_custom').hide();
          }
          if($('.recherche_label_jecherche').length) {
            $('.recherche_label_jecherche').show();
          }

          if(response.response === false) {
            // alert("Aucune offre n'existe.");
            if($('.offre_vide').length == 0){
              $(".button_reinitialisation_filtres").prepend("<p class=\"offre_vide\">Aucune offre n'existe</p>")
              $(".body_shortcode_recherche").show();
              $(".elementor-element-3423e2c").show();
              $(".elementor-element-d43dec3").show();
              $("#show").hide();
              $("#retour").show();
            } else{
              
              $(".button_reinitialisation_filtres").remove("<p class=\"offre_vide\">Aucune offre n'existe</p>")
              $(".body_shortcode_recherche").hide();
              $(".elementor-element-3423e2c").hide(); 
              $(".elementor-element-d43dec3").hide();
              $("#show").show();
              $("#retour").hide();
            }
          } else if($('.offre_vide').length){
              $(".offre_vide").remove()
          }
          

          var elements = [];
          $.each(response.results, function (i, v) {
            if (this.category_id === 14) {
              // vigne
              var myIcon = L.icon({
                iconUrl:
                  "../wp-content/plugins/flk-custom/assets/images/pin_bleu.png",
                iconSize: [29, 43],
                iconAnchor: [14, 43],
              });
              var cepage = this.Cepage !== null ? this.Cepage : "-" ; 
              var pop_content =
                '<div class="popup_marker-vigne' +
                this.titre +
                '">' +
                '<p class="titre"> ' +
                this.titre +
                "</p>" +
                "<p class='text-popup_marker-vigne'> Offre numéro : <span>" +
                this.Offre_titre +
                "</span></p>" +
                "<p class='text-popup_marker-vigne'> Commune : <span>" +
                this.Commune_name +
                "</span></p>" +
                "<p class='text-popup_marker-vigne'> Cépage : <span>" +
                cepage +
                "</span></p>" +
                "<p class='text-popup_marker-vigne'> Cession : <span>" +
                this.Cession +
                "</span></p>" +
                '<div style="text-align:center;"><a class="button-popup_marker-vigne" href="' +
                this.Offre_url +
                '" target="_blank" > VOIR L\'OFFRE</a></div>';
              ("</div>");
              var coordonneesgps = v.Coordonees_gps;
              switch (coordonneesgps) {
                case false:
                  var lon = false;
                  var lat = false;
                  break;
                case null:
                  var lon = false;
                  var lat = false;
                  break;
                case !v.Coordonees_gps.includes(";"):
                  var lon = false;
                  var lat = false;
                  break;
                default:
                if (v.Coordonees_gps.includes(";")) {
                  var lon = v.Coordonees_gps.split(";")[0];
                  var lat = v.Coordonees_gps.split(";")[1];
                } else {
                  var lon = false;
                  var lat = false;
                }
                break;
            }
          } else if (this.category_id === 13) {
            // cuvage
            var myIcon = L.icon({
              iconUrl:
                "../wp-content/plugins/flk-custom/assets/images/pin_vert.png",
              iconSize: [29, 43],
              iconAnchor: [14, 43],
            });
            var pop_content =
              '<div class="popup_marker-cuvage ' +
              this.titre +
              '">' +
              '<p class="titre"> ' +
              this.titre +
              "</p>" +
              "<p class='text-popup_marker-cuvage'> Offre numéro : <span>" +
              this.Offre_titre +
              "</span></p>" +
              "<p class='text-popup_marker-cuvage'> Commune : <span>" +
              this.Commune_name +
              "</span></p><div style='text-align:center;'>" +
              '<a class="button-popup_marker-cuvage" href="' +
              this.Offre_url +
              '" target="_blank" > VOIR L\'OFFRE</a></div>';
            ("</div>");
            //console.log(v.Coordonees_gps);
            var coordonneesgps = v.Coordonees_gps;
            switch (coordonneesgps) {
              case false:
                var lon = false;
                var lat = false;
                break;
              case null:
                var lon = false; 
                var lat = false;
                break;
              case !v.Coordonees_gps.includes(";"):
                var lon = false;
                var lat = false;
                break;
              default:
                if (v.Coordonees_gps.includes(";")) {
                  var lon = v.Coordonees_gps.split(";")[1];
                  var lat = v.Coordonees_gps.split(";")[0];
                } else {
                  var lon = false;
                  var lat = false;
                }
                break;
            }
          } else if(this.category_id === 20) {
            // offre complète
            // debugger
            var myIcon = L.icon({
              iconUrl:
                "../wp-content/plugins/flk-custom/assets/images/pin_saumon.png",
              iconSize: [29, 43],
              iconAnchor: [14, 43],
            });
            var Commune_name = this.Commune_name !== null ? this.Commune_name : "-" ; 
            var pop_content =
              '<div class="popup_marker-complete ' +
              this.titre +
              '">' +
              '<p class="titre"> ' +
              this.titre + ' - ' + this.Type_offre +
              "</p>" +
              "<p class='text-popup_marker-complete'> Offre numéro : <span>" +
              this.Offre_id +
              "</span></p>" +
              "<p class='text-popup_marker-complete'> Titre : <span>" +
              this.Offre_titre +
              "</span></p>" +
              "<p class='text-popup_marker-complete'> Commune : <span>" +
              Commune_name +
              "</span></p><div style='text-align:center;'>" +
              '<a class="button-popup_marker-complete" href="' +
              this.Offre_url +
              '" target="_blank" > VOIR SUR LE SITE</a></div>';
            ("</div>");
            //console.log(v.Coordonees_gps);
            var coordonneesgps = v.Coordonees_gps;
            switch (coordonneesgps) {
              case false:
                var lon = false;
                var lat = false;
                break;
              case null:
                var lon = false;
                var lat = false;
                break;
              case !v.Coordonees_gps.includes(";"):
                var lon = false;
                var lat = false;
                break;
              default:
                if (v.Coordonees_gps.includes(";")) {
                  var lon = v.Coordonees_gps.split(";")[1];
                  var lat = v.Coordonees_gps.split(";")[0];
                } else {
                  var lon = false;
                  var lat = false;
                }
                break;
            }
          }
          //debugger
          console.log(v);

          elements[i] = {
            lon: lon,
            lat: lat,
            pop_content: pop_content,
            icon: myIcon,
          };
        });
        var markerArray = add_marker(map, elements);

        return markerArray;

        },
        error: function(response) {

          // On gère l'affichage du spinner
          // Ici on l'affiche et on masque le texte "Recherche"
          if($('.loader_custom').length) {
            $('.loader_custom').hide();
          }
          if($('.recherche_label_jecherche').length) {
            $('.recherche_label_jecherche').show();
          }
        }
        
      });
    }
    function reinit_post(data, map, markerArray) {
      $.ajax({
        type: "POST",
        url: "/wp-json/flk_api/v1/reinitPost",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({ data: data }),
        complete: function () {},
        success: function (response) {
          var elements = [];
          $.each(response.results, function (i, v) {
            coordonneesgps_result = v.Coordonees_gps;
            console.log(coordonneesgps_result)
            if (this.category_id === 14) {
              // vigne
              var myIcon = L.icon({
                iconUrl:
                  "../wp-content/plugins/flk-custom/assets/images/pin_bleu.png",
                iconSize: [29, 43],
                iconAnchor: [14, 43],
              });
              var cepage = this.Cepage !== null ? this.Cepage : "-" ; 
              var pop_content =
                '<div class="popup_marker-vigne' +
                this.titre +
                '">' +
                '<p class="titre"> ' +
                this.titre +
                "</p>" +
                "<p class='text-popup_marker-vigne'> Offre numéro : " +
                this.Offre_titre +
                "</p>" +
                "<p class='text-popup_marker-vigne'> Commune : " +
                this.Commune_name +
                "</p>" +
                "<p class='text-popup_marker-vigne'> Cépage : " +
                cepage +
                "</p>" +
                "<p class='text-popup_marker-vigne'> Cession : " +
                this.Cession +
                "</p>" +
                '<div style="text-align:center;"><a class="button-popup_marker-vigne" href="' +
                this.Offre_url +
                '" target="_blank" > VOIR L\'OFFRE</a></div>';
              ("</div>");
              var coordonneesgps = v.Coordonees_gps;
              switch (coordonneesgps) {
                case false:
                  var lon = false;
                  var lat = false;
                  break;
                case null:
                  var lon = false;
                  var lat = false;
                  break;
                case !v.Coordonees_gps.includes(";"):
                  var lon = false;
                  var lat = false;
                  break;
                default:
                  if (v.Coordonees_gps.includes(";")) {
                    var lon = v.Coordonees_gps.split(";")[0];
                    var lat = v.Coordonees_gps.split(";")[1];
                  } else {
                    var lon = false;
                    var lat = false;
                  }
                  break;
              }
            } else if (this.category_id === 13) {
              // cuvage
              var myIcon = L.icon({
                iconUrl:
                  "../wp-content/plugins/flk-custom/assets/images/pin_vert.png",
                iconSize: [29, 43],
                iconAnchor: [14, 43],
              });
              var pop_content =
                '<div class="popup_marker-cuvage ' +
                this.titre +
                '">' +
                '<p class="titre"> ' +
                this.titre +
                "</p>" +
                "<p class='text-popup_marker-cuvage'> Offre numéro : " +
                this.Offre_titre +
                "</p>" +
                "<p class='text-popup_marker-cuvage'> Commune : " +
                this.Commune_name +
                "</p><div style='text-align:center;'>" +
                '<a class="button-popup_marker-cuvage" href="' +
                this.Offre_url +
                '" target="_blank" > VOIR L\'OFFRE</a></div>';
              ("</div>");
              //console.log(v.Coordonees_gps);
              var coordonneesgps = v.Coordonees_gps;
              switch (coordonneesgps) {
                case false:
                  var lon = false;
                  var lat = false;
                  break;
                case null:
                  var lon = false;
                  var lat = false;
                  break;
                case !v.Coordonees_gps.includes(";"):
                  var lon = false;
                  var lat = false;
                  break;
                default:
                  if (v.Coordonees_gps.includes(";")) {
                    var lon = v.Coordonees_gps.split(";")[1];
                    var lat = v.Coordonees_gps.split(";")[0];
                  } else {
                    var lon = false;
                    var lat = false;
                  }
                  break;
              }
            } else if(this.category_id === 20) {
              // offre complète
              var myIcon = L.icon({
                iconUrl:
                  "../wp-content/plugins/flk-custom/assets/images/pin_saumon.png",
                iconSize: [29, 43],
                iconAnchor: [14, 43],
              });
              var pop_content =
                '<div class="popup_marker-complete ' +
                this.titre +
                '">' +
                '<p class="titre"> ' +
                this.titre + ' - ' + this.Type_offre +
                "</p>" +
                "<p class='text-popup_marker-complete'> Offre numéro : " +
                this.Offre_id +
                "</p>" +
                "<p class='text-popup_marker-complete'> Titre : " +
                this.Offre_titre +
                "</p>" +
                "<p class='text-popup_marker-complete'> Commune : " +
                this.Commune_name +
                "</p><div style='text-align:center;'>" +
                '<a class="button-popup_marker-complete" href="' +
                this.Offre_url +
                '" target="_blank" > VOIR SUR LE SITE</a></div>';
              ("</div>");
              //console.log(v.Coordonees_gps);
              var coordonneesgps = v.Coordonees_gps;
              switch (coordonneesgps) {
                case false:
                  var lon = false;
                  var lat = false;
                  break;
                case null:
                  var lon = false;
                  var lat = false;
                  break;
                case !v.Coordonees_gps.includes(";"):
                  var lon = false;
                  var lat = false;
                  break;
                default:
                  if (v.Coordonees_gps.includes(";")) {
                    var lon = v.Coordonees_gps.split(";")[1];
                    var lat = v.Coordonees_gps.split(";")[0];
                  } else {
                    var lon = false;
                    var lat = false;
                  }
                  break;
              }
            }

            elements[i] = {
              lon: lon,
              lat: lat,
              pop_content: pop_content,
              icon: myIcon,
            };
          });
          var markerArray = add_marker(map, elements);

          $('#reinit_filtres').hide();

          return markerArray;
        },
      });
    }

    function ajouter_shorcode(element) {
      var shorcode = $(element).attr("data-shorcode");
      //var element_parent = $(element).closest(".flk");
      var new_element = $(element).attr("data-new");
      var data_cuvage = $(element).attr("data-cuve");
      if (shorcode && new_element === "true" && data_cuvage === "0") {
        print_shorcode(shorcode, $(element));
      }
    }
    function toggle_bloc_parcelle_cuvage(event) {
      $(event.target)
        .closest(".flk")
        .find(".body_shortcode_block")
        .removeClass("block_close")
        .removeClass("block_close_old");
        //debugger
      $(event.target)
        .closest(".flk")
        .find(".header_shortcode_block")
        .toggleClass("button_hide");
    }
    function remove_element(event) {
      $(event.target).closest(".flk").remove();
      // todo message de confirmation
    }
    // ~~ Fonction de suppression du bloc Parcelle/Cuvage lors du changement de choix dans la dropdownlist "Vigne ou cuvage"
    function remove_shortcode(shortcodeToTarget) {
      shortcodeToTarget.remove();
    }
    function required_validation_function(event) {
      var required_validation = false;
    /*$(".flk")
        .find("input")
        .each(function () {
          if ($(this).prop("required")) {
            // is required
            // on vérifie si une valeur est entrée
            var prenom = $('#field_62e10ba1f1f35').val(); 
            console.log(prenom);
            if ($(this).val() === "") {
              if ($(this).next("span.flk_erreur").length === 0) {
                var message_erreur =
                  '<span class="flk_erreur"> Merci de completer ce champ </span>';
                $(message_erreur).insertAfter($(this));
              }
              $(this).addClass("error");
              required_validation = false;
            } else {
              $(this).next("span.flk_erreur").remove();
              $(this).removeClass("error");
              required_validation = true;
            }
          }
        });
      $(".flk")
        .find("select")
        .each(function () {
          if ($(this).prop("required")) {
            // is required
            // on vérifie si une valeur est entrée
            if ($(this).val() === "_") {
              if ($(this).next("span.flk_erreur").length === 0) {
                var message_erreur =
                  '<span class="flk_erreur"> Merci de completer ce champ </span>';
                $(message_erreur).insertAfter($(this));
              }
              $(this).addClass("error");
              required_validation = false;
            } else {
              $(this).next("span.flk_erreur").remove();
              $(this).removeClass("error");
              required_validation = true;
            }
          }
        });
      $(".flk")
        .find(".fieldset_required.fieldset_radio")
        .each(function () {
          // input radio
          var all_checked = false;
          $(this)
            .find("input")
            .each(function () {
              if ($(this).is(":checked")) {
                all_checked = true;
              }
            });
          // on vérifie si tout les radios sont check
          if (all_checked === false) {
            if ($(this).find("span.flk_erreur").length === 0) {
              var message_erreur =
                '<span class="flk_erreur"> Merci de choisir une option </span>';
              $(this).append($(message_erreur));
            }
            $(this).addClass("error");
            required_validation = false;
          } else {
            $(this).find("span.flk_erreur").remove();
            $(this).removeClass("error");
            required_validation = true;
          }
        });*/

    $('.flk_erreur').remove();
		// on recupere les valeurs des inputs du profil
    var nom = $('#field_62e10a377a48d').val();
    var prenom = $('#field_62e10ba1f1f35').val();
    var adresse = $('#field_62e10c29f1f37').val();
    var codepostal = $('#field_62e10c2ff1f38').val();
    var commune_profil = $('#field_62e10c55f1f39').val();
    var telephone = $('#field_62e10c5cf1f3a').val();
    var email = $('#field_62e10c6af1f3b').val();
    var date_dispo = $('#field_62e10cda103a1').val();

    //on recupere les valeurs des select du profil
    var profil = $('#field_62e10c7af1f3c').val();
    var parcelle_cuvage = $('#field_62e10cf1103a2').val();

    //on recupere les valeurs des checkbox du profil
    var context = ($('input[name=contexte_de_l’offre]:checked').val());
    
    // on recupere les valeurs des inputs
		var section = $('#field_62eb9f240fb78').val(); 
		var parcelle = $('#field_62eb9f430fb79').val(); 
		var surface = $('#field_62ebb69f13630').val(); 
		var coordonees = $('#field_62ebb6d713631').val(); 
    
    //console.log(section);
		
		//on recupere les valeurs des selects
		var commune = $('#field_62eb9e690fb77').val(); 
		var nature = $('#field_62ebb7193e5cb').val(); 
		var AOC = $('#field_62ebb8e692dd6').val();
		var cepage = $('#field_62ebbb020d677').val();
		var taille = $('#field_62ebbb800d678').val();
		var bio = $('#field_62ebbb9c0d679').val();
		var cession = $('#field_62ebbbfb58c64').val();

    // on verifier si il contien une valeur

    // si l'input est vide profil
      // nom
			if ( nom.length < 1){
				$('#field_62e10a377a48d').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');
			}
			else{
				$('.fieldset_votre_nom .flk_erreur').remove();
			}
			// prenom
			if(prenom.length < 1){
				$('#field_62e10ba1f1f35').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');
			}else{ 
				 $('.fieldset_votre_prenom .flk_erreur').remove();
			}
			// adresse
			if(adresse.length < 1){
				$('#field_62e10c29f1f37').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');	  
			}else{
				 $('.fieldset_adresse .flk_erreur').remove();
			}
			// codepostal
			if(codepostal.length < 1){
				$('#field_62e10c2ff1f38').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');	  
			}else{
				 $('.fieldset_code_postal .flk_erreur').remove();
			}
      // commune_profil
			if(commune_profil.length < 1){
				$('#field_62e10c55f1f39').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');	  
			}else{
				 $('.fieldset_commune .flk_erreur').remove();
			}
      // telephone
			if(telephone.length < 1){
				$('#field_62e10c5cf1f3a').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');	  
			}else{
				 $('.fieldset_telephone .flk_erreur').remove();
			}
      // email
			if(email.length < 1){
				$('#field_62e10c6af1f3b').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');	  
			}else{
				 $('.fieldset_email .flk_erreur').remove();
			}
      // date_dispo
			if(date_dispo.length < 1){
				$('#field_62e10cda103a1').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');	  
			}else{
				 $('.fieldset_date_de_disponibilite .flk_erreur').remove();
			}

      // si les select sont vide
      //profil
      if(profil === '_' ){
        $('#field_62e10c7af1f3c').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');
      }else{
        $('.fieldset_profil .flk_erreur').remove();
      }
      //parcelle_cuvage
      if(parcelle_cuvage === '_' ){
        $('#field_62e10cf1103a2').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');
      }else{
        $('.fieldset_vigne_ou_cuvage .flk_erreur').remove();
      }

      // si la checkbox est vide 
      if(typeof context === 'undefined' ){
        $('.fieldset_contexte_de_l’offre').after('<span class="flk_erreur"> Merci de compléter ce champ </span>');
      }else{
        $('.fieldset_contexte_de_l’offre .flk_erreur').remove();
      }

    // fin profil

		// si l'input est vide parcelle
			// section
			/*if(section.length < 1){
				$('#field_62eb9f240fb78').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}
			else{
				$('.fieldset_section .flk_erreur').remove();
			}
			// parcelle
			if(parcelle.length < 1){
				$('#field_62eb9f430fb79').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{ 
				 $('.fieldset_numero_parcelle .flk_erreur').remove();
			}
			// surface
			if(surface.length < 1){
				$('#field_62ebb69f13630').after('<span class="flk_erreur"> Merci de completer ce champ </span>');	  
			}else{
				 $('.fieldset_surface .flk_erreur').remove();
			}
			// coordonees
			if(coordonees.length < 1){
				$('#field_62ebb6d713631').after('<span class="flk_erreur"> Merci de completer ce champ </span>');	  
			}else{
				 $('.fieldset_coordonees_gps .flk_erreur').remove();
			}
		
			// si le select est vide
			// commune
			if(commune === '_' ){
				  $('#field_62eb9e690fb77').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_code_insee .flk_erreur').remove();
			}
			// nature
			if(nature === '_' ){
				  $('#field_62ebb7193e5cb').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_nature_de_l’offre .flk_erreur').remove();
			}
		if($('.sous_group_vigne_et_vin').css("display") !== "none") {
			// AOC 
			if(AOC === '_' ){
				  $('#field_62ebb8e692dd6').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_aoc .flk_erreur').remove();
			}
			// cepage 
			if(cepage === '_' ){
				  $('#field_62ebbb020d677').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_cepage .flk_erreur').remove();
			}
			// taille 
			if(taille === '_' ){
				  $('#field_62ebbb800d678').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_taille_de_la_vigne .flk_erreur').remove();
			}
			// bio 
			if(bio === '_' ){
				  $('#field_62ebbb9c0d679').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_agriculture_biologique .flk_erreur').remove();
			}
			// cession 
			if(cession === '_' ){
				  $('#field_62ebbbfb58c64').after('<span class="flk_erreur"> Merci de completer ce champ </span>');
			}else{
				$('.fieldset_type_de_cession_parcelle .flk_erreur').remove();
			}
		}else{
			//
		}*/
	
			

      if( $('.body_parcelle_block').hasClass('block_close') ){
        if ( nom.length > 1 && prenom.length > 1 && adresse.length > 1 && codepostal.length > 1 && commune_profil.length > 1 && telephone.length > 1 && email.length > 1 && date_dispo.length > 1 && profil != '_' && parcelle_cuvage != '_' && typeof context !== 'undefined'){
          required_validation = true;
          //debugger
        }else{
          $('.pre_validation input').after('<span class="flk_erreur global"> Merci de compléter les champs dans votre profil </span>');
        }
      }else if(!$('.body_parcelle_block').hasClass('block_close') ){
        if ( nom.length > 1 && prenom.length > 1 && adresse.length > 1 && codepostal.length > 1 && commune_profil.length > 1 && telephone.length > 1 && email.length > 1 && date_dispo.length > 1 && profil != '_' && parcelle_cuvage != '_' && typeof context !== 'undefined'){
          //required_validation = true;
          $('.pre_validation input').after('<span class="flk_erreur global"> Merci de valide le formulaire ou les formulaires </span>');
        }else{
          $('.pre_validation input').after('<span class="flk_erreur global"> Merci de compléter les champs dans votre profil et de valider le formulaire ou les formulaires </span>');
        }
      }  

      return required_validation;
    }

    function init_carte(lat, lon, map) {
      // Fonction d'initialisation de la carte // init la carte sans points
      // On initialise la latitude et la longitude de Paris (centre de la carte)
      // Créer l'objet "map" et l'insèrer dans l'élément HTML qui a l'ID "flk_map"
       map = L.map('flk_map').setView([lat, lon], 7);
            // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
            L.tileLayer
            .chinaProvider("Google.Satellite.Annotion", {
                // Il est toujours bien de laisser le lien vers la source des données
                attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            }).addTo(map);
            return map
    }

    function add_marker(map, elements) {
      // var markerArray = JSON.parse(sessionStorage.getItem("markerArray"));
      // markerArray.clearLayers();
      // if (markerArray !== null) {
      //   for (i = 0; i < markerArray.length; i++) {
      //     if (markerArray[i] !== null) {
      //       debugger;
      //       map.removeLayer(markerArray[i]);
      //     }
      //   }
      // }
      // map.invalidateSize();
      map.eachLayer(function (layer) {
        if (layer._url === undefined) {
          map.removeLayer(layer);
        }
      });
      var markerArray = [];
      
      if (elements.length > 0) {
        for (var i = 0; i < elements.length; i++) {
          if (
            elements[i].lon !== false &&
            elements[i].lon !== null &&
            elements[i].lon !== "undefined"
          ) {
            if (elements[i].pop_content !== "") {
              if (elements[i].icon) {
                marker = new L.marker([elements[i].lat, elements[i].lon], {
                  icon: elements[i].icon,
                  minZoom: 25
                })
                // bloquer la popup
                  .bindPopup(elements[i].pop_content)
                  .addTo(map);
              } else {
                marker = new L.marker([elements[i].lat, elements[i].lon])
                  .bindPopup(elements[i].pop_content)
                  .addTo(map);
              }
            } else {
              if (elements[i].icon) {
                marker = new L.marker([elements[i].lat, elements[i].lon], {
                  icon: elements[i].icon,
                }).addTo(map);
              } else {
                marker = new L.marker([elements[i].lat, elements[i].lon]).addTo(
                  map
                );
              }
            }
            markerArray.push(marker);
          }
        }
        var group = L.featureGroup(markerArray).addTo(map);
        //console.log(group);
        map.fitBounds(group.getBounds());
        //map.fitBounds(group.getBounds(), {padding: [50,50]});
        // map.fitBounds([
        //   [40.712, -74.227],
        //   [40.774, -74.125]
        // ]);
      }
      // on stock en session les markers
      sessionStorage.setItem(
        markerArray,
        JSON.stringify(markerArray, getCircularReplacer())
      );
      return markerArray;
    }

    function getCircularReplacer() {
      const seen = new WeakSet();
      return (key, value) => {
        if (typeof value === "object" && value !== null) {
          if (seen.has(value)) {
            return;
          }
          seen.add(value);
        }
        return value;
      };
    }

    function reorganisation_cuvage(lastChild) {
      /* #### Mise d'une DIV dans une DIV pour le groupe CUVAGE #### */
      /* ~~ Réorganisation de la partie Lieu ~~ */
      /* Ajout de la div souhaitée */

      lastChild
        .find("div.block_cuvage_creation .sous_group_lieu")
        .append("<div class='groupe_lieu'></div>");
      /* Fin */
      /* Déplacement des inputs */
      var groupelieu = lastChild.find(".groupe_lieu");
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_lieu fieldset.fieldset_code_insee"
        )
        .appendTo(lastChild.find(".groupe_lieu"));
      /* Fin */
      /* ~~ Fin ~~ */
      /* ~~ Réorganisation de la partie Cuvage ~~ */
      /* Ajout de la div souhaitée */
      lastChild
        .find("div.block_cuvage_creation .sous_group_cuvage .sous_group_titre")
        .after(
          "<div class='groupe_surface_capacite_effluents_stockage'></div>"
        );
      /* Fin */
      /* Déplacement des inputs */
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_cuvage fieldset.fieldset_surface_du_cuvage"
        )
        .appendTo(
          lastChild.find(".groupe_surface_capacite_effluents_stockage")
        );
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_cuvage fieldset.fieldset_capacite_du_cuvage"
        )
        .appendTo(
          lastChild.find(".groupe_surface_capacite_effluents_stockage")
        );
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_cuvage fieldset.fieldset_gestion_des_effluents"
        )
        .appendTo(
          lastChild.find(".groupe_surface_capacite_effluents_stockage")
        );
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_cuvage fieldset.fieldset_stockage_bouteille"
        )
        .appendTo(
          lastChild.find(".groupe_surface_capacite_effluents_stockage")
        );
      /* Fin */
      /* ~~ Fin ~~ */
      /* ~~ Réorganisation de la partie Types de cuves ~~ */
      /* Ajout de la div souhaitée */
      lastChild
        .find(
          "div.block_cuvage_creation fieldset.fieldset_types_de_cuves input[type='hidden']"
        )
        .after("<div class='groupe_radiobuttons_cuves'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChild
        .find(
          "div.block_cuvage_creation fieldset.fieldset_types_de_cuves .checkbox_group"
        )
        .appendTo(lastChild.find(".groupe_radiobuttons_cuves"));
      /* Fin */
      /* ~~ Fin ~~ */
      /* ~~ Réorganisation de la partie Compléments ~~ */
      /* Ajout de la div souhaitée */
      lastChild
        .find("div.block_cuvage_creation .sous_group_complement")
        .append("<div class='groupecuv_typescession_observations'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_complement fieldset.fieldset_type_de_cession_cuvage"
        )
        .appendTo(lastChild.find(".groupecuv_typescession_observations"));
      lastChild
        .find(
          "div.block_cuvage_creation .sous_group_complement fieldset.fieldset_cuvage_observation"
        )
        .appendTo(lastChild.find(".groupecuv_typescession_observations"));
      /* Fin */
      /* ~~ Fin ~~ */
      /* #### FIN de l'inception #### */

      /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

      /* #### Mise à jour des select pour qu'ils ressemblent au figma #### */
      lastChild.find("select").each(function () {
        var $this = $(this),
          numberOfOptions = $(this).children("option").length;

        $this.addClass("select-hidden");
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        var $styledSelect = $this.next("div.select-styled");
        console.log($this.children("option").eq(0).text());
        $styledSelect.text($this.children("option").eq(0).text());

        var $list = $("<ul />", {
          class: "select-options",
        }).insertAfter($styledSelect);
        console.log($list);

        for (var i = 0; i < numberOfOptions; i++) {
          var title_with_underscore = $this
            .children("option")
            .eq(i)
            .val()
            .replace(/\_/g, " ");
          var title_to_capitalize =
            title_with_underscore.substr(0, 1).toUpperCase() +
            title_with_underscore.substr(1);
          $("<li />", {
            text: $this.children("option").eq(i).text(),
            rel: $this.children("option").eq(i).val(),
            title: title_to_capitalize,
          }).appendTo($list);
        }

        var $listItems = $list.children("li");

        $styledSelect.click(function (e) {
          e.stopPropagation();
          $("div.select-styled.active")
            .not(this)
            .each(function () {
              $(this).removeClass("active").next("ul.select-options").hide();
            });
          $(this).toggleClass("active").next("ul.select-options").toggle();
        });

        $listItems.click(function (e) {
          e.stopPropagation();
          $styledSelect.text($(this).text()).removeClass("active");
          $this.val($(this).attr("rel"));
          $list.hide();
        });

        $(document).click(function () {
          $styledSelect.removeClass("active");
          $list.hide();
        });
      });

      $(".ajout_cuvage .select-styled").on("click", function (e) {
        e.stopPropagation();
        $(".ajout_cuvage .select-styled.active")
          .parents(".fieldset_select")
          .addClass("focused");
      });

      $('input').each(function() {
        var inputValue = $(this).val();
        if(inputValue != "") {
          $(this).closest('.fieldset_text').addClass('focused');  
        } else {
          $(this).closest('.fieldset_text').removeClass('focused');  
        }
      });
      /* #### FIN Mise à jour des select #### */
    }

    function reorganisation_parcelles(lastChildParcelle) {
      /* #### Mise d'une DIV dans une DIV pour le groupe PARCELLE #### */
      /* ~~ Réorganisation de la partie Commune / Section cadastrale / N°Parcelle ~~ */
      /* Ajout de la div souhaitée */
      lastChildParcelle
        .find("div.block_parcelle_creation .ajouter_une_parcelle")
        .append(
          "<div class='groupe_commune_sectioncadastrale_numparcelle'></div>"
        );
      /* Fin */
      /* Déplacement des inputs */
      lastChildParcelle
        .find("div.block_parcelle_creation fieldset.fieldset_code_insee")
        .appendTo(
          lastChildParcelle.find(
            ".groupe_commune_sectioncadastrale_numparcelle"
          )
        );
      lastChildParcelle
        .find("div.block_parcelle_creation fieldset.fieldset_section")
        .appendTo(
          lastChildParcelle.find(
            ".groupe_commune_sectioncadastrale_numparcelle"
          )
        );
      lastChildParcelle
        .find("div.block_parcelle_creation fieldset.fieldset_numero_parcelle")
        .appendTo(
          lastChildParcelle.find(
            ".groupe_commune_sectioncadastrale_numparcelle"
          )
        );
      /* Réorganisation du bloc num parcelle */
      lastChildParcelle
        .find("div.block_parcelle_creation fieldset.fieldset_numero_parcelle")
        .prepend('<div class="label_input_numparcelle"></div>');
      lastChildParcelle
        .find(
          "div.block_parcelle_creation fieldset.fieldset_numero_parcelle label"
        )
        .appendTo(lastChildParcelle.find(".label_input_numparcelle"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation fieldset.fieldset_numero_parcelle input"
        )
        .appendTo(lastChildParcelle.find(".label_input_numparcelle"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation fieldset.fieldset_numero_parcelle .instructions"
        )
        .appendTo(lastChildParcelle.find(".label_input_numparcelle"));
      /* Fin */
      /* ~~ Fin ~~ */

      /* ~~ Réorganisation de la partie Surface / Coordonnées GPS ~~ */
      /* Ajout de la div souhaitée */
      lastChildParcelle
        .find("div.block_parcelle_creation .ajouter_une_parcelle")
        .append("<div class='groupe_surface_coordgps'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChildParcelle
        .find("div.block_parcelle_creation fieldset.fieldset_surface")
        .appendTo(lastChildParcelle.find(".groupe_surface_coordgps"));
      lastChildParcelle
        .find("div.block_parcelle_creation fieldset.fieldset_coordonees_gps")
        .appendTo(lastChildParcelle.find(".groupe_surface_coordgps"));
      /* Fin */
      /* ~~ Fin ~~ */

      /* ~~ Réorganisation de la partie Nature de l'offre / Type de sol ~~ */
      /* Ajout de la div souhaitée */
      lastChildParcelle
        .find("div.block_parcelle_creation .sous_group_terrain")
        .append("<div class='groupe_nature_type'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_terrain fieldset.fieldset_nature_de_l’offre"
        )
        .appendTo(lastChildParcelle.find(".groupe_nature_type"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_terrain fieldset.fieldset_type_de_sol"
        )
        .appendTo(lastChildParcelle.find(".groupe_nature_type"));
      /* Fin */
      /* ~~ Fin ~~ */

      /* ~~ Réorganisation de la partie AOC / Cépage / Taille / Agriculture bio ~~ */
      /* Ajout de la div souhaitée */
      lastChildParcelle
        .find("div.block_parcelle_creation .sous_group_vigne_et_vin")
        .append("<div class='groupe_aoc_cepage_taille_agribio'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_vigne_et_vin fieldset.fieldset_aoc "
        )
        .appendTo(lastChildParcelle.find(".groupe_aoc_cepage_taille_agribio"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_vigne_et_vin fieldset.fieldset_cepage "
        )
        .appendTo(lastChildParcelle.find(".groupe_aoc_cepage_taille_agribio"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_vigne_et_vin fieldset.fieldset_taille_de_la_vigne"
        )
        .appendTo(lastChildParcelle.find(".groupe_aoc_cepage_taille_agribio"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_vigne_et_vin fieldset.fieldset_agriculture_biologique"
        )
        .appendTo(lastChildParcelle.find(".groupe_aoc_cepage_taille_agribio"));
      /* Fin */
      /* ~~ Fin ~~ */

      /* ~~ Réorganisation de la partie vignification ~~ */
      /* Ajout de la div souhaitée */
      lastChildParcelle
        .find("div.block_parcelle_creation .sous_group_vigne_et_vin")
        .append("<div class='groupe_vignifi'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_vigne_et_vin fieldset.fieldset_vinification "
        )
        .appendTo(lastChildParcelle.find(".groupe_vignifi"));
      /* Fin */
      /* ~~ Fin ~~ */

      /* ~~ Réorganisation de la partie Type de cession / observations ~~ */
      /* Ajout de la div souhaitée */
      lastChildParcelle
        .find("div.block_parcelle_creation .sous_group_complements")
        .append("<div class='groupe_typecession_observations'></div>");
      /* Fin */
      /* Déplacement des inputs */
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_complements fieldset.fieldset_type_de_cession_parcelle"
        )
        .appendTo(lastChildParcelle.find(".groupe_typecession_observations"));
      lastChildParcelle
        .find(
          "div.block_parcelle_creation .sous_group_complements fieldset.fieldset_parcelle_observations "
        )
        .appendTo(lastChildParcelle.find(".groupe_typecession_observations"));
      /* Fin */
      /* ~~ Fin ~~ */
      /* #### FIN de l'inception #### */

      /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

      /* #### Mise à jour des select pour qu'ils ressemblent au figma #### */
      lastChildParcelle.find("select").each(function () {
        var $this = $(this),
          numberOfOptions = $(this).children("option").length;

        $this.addClass("select-hidden");
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        var $styledSelect = $this.next("div.select-styled");
        console.log($this.children("option").eq(0).text());
        $styledSelect.text($this.children("option").eq(0).text());

        var $list = $("<ul />", {
          class: "select-options",
        }).insertAfter($styledSelect);
        console.log($list);

        for (var i = 0; i < numberOfOptions; i++) {
          var title_with_underscore = $this
            .children("option")
            .eq(i)
            .val()
            .replace(/\_/g, " ");
          var title_to_capitalize =
            title_with_underscore.substr(0, 1).toUpperCase() +
            title_with_underscore.substr(1);
          $("<li />", {
            text: $this.children("option").eq(i).text(),
            rel: $this.children("option").eq(i).val(),
            title: title_to_capitalize,
          }).appendTo($list);
        }

        var $listItems = $list.children("li");

        $styledSelect.click(function (e) {
          e.stopPropagation();
          $("div.select-styled.active")
            .not(this)
            .each(function () {
              $(this).removeClass("active").next("ul.select-options").hide();
            });
          $(this).toggleClass("active").next("ul.select-options").toggle();
        });

        $listItems.click(function (e) {
          e.stopPropagation();
          $styledSelect.text($(this).text()).removeClass("active");
          $this.val($(this).attr("rel"));
          $list.hide();
        });

        // $styledSelect.each(function() {
        //   $("div.select-styled.active")
        //     .not(this)
        //     .each(function () {
        //       $(this).removeClass("active").next("ul.select-options").hide();
        //     });
        //   $(this).toggleClass("active").next("ul.select-options").toggle();
        // });

        // $listItems.each(function() {
        //   console.log($(this).text());
        //   $styledSelect.text($(this).text()).removeClass("active");
        //   $this.val($(this).attr("rel"));
        //   $list.hide();
        // });

        $(document).click(function () {
          $styledSelect.removeClass("active");
          $list.hide();
        });
      });

      $(".ajout_parcelle .select-styled").on("click", function (e) {
        e.stopPropagation();
        $(".ajout_parcelle .select-styled.active")
          .parents(".fieldset_select")
          .addClass("focused");
      });

      $('select').each(function() {
        var selectValue = $(this).val();
        if(selectValue != "_") {
          $(this).closest('.fieldset_select').addClass('focused'); 
        } else {
          $(this).closest('.fieldset_select').removeClass('focused');  
        }
      });

      $('input').each(function() {
        var inputValue = $(this).val();
        if(inputValue != "") {
          $(this).closest('.fieldset_text').addClass('focused');  
        } else {
          $(this).closest('.fieldset_text').removeClass('focused');  
        }
      });
    }

    /* #### FIN Mise à jour des select #### */

    //    $('.button_ajout_shortcode').on('click', function(event) {
    //         // on cache ou show le block
    //         $(event.target).closest(".body_shortcode_block").toggleClass('block_close');
    //         $(event.target).closest(".flk").find(".header_shortcode_block").toggleClass('button_hide');
    //    })
  });

})(jQuery);
