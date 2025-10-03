(function ($) {
    $(document).ready(function () {

        // AJAX search call
        function ajax_call(ajax_url, element, action, callback) {
            //debugger
            // do a POST ajax call
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: ({
                    action: "edit_BDD",
                    element: element,
                }),
                success: function (response) {
                    callback(response)
                }
            });
        }
    });
})(jQuery);