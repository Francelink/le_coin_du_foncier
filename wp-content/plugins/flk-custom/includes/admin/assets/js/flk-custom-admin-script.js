(function ($) {
  $(document).ready(function () {
    // AJAX search call
    $("form.getGEOJSON").submit(function (event) {
      event.preventDefault();
      var url = $(event.target).find('input[name="url"]').val();
      var code_insee = $(event.target).find('input[name="code_insee"]').val();
      var section = $(event.target).find('input[name="section"]').val();
      var numero = $(event.target).find('input[name="numero"]').val();
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
        console.log(response, center, coordonnee, features);
        debugger;
      });
    });
  });
})(jQuery);
