$(document).ready(function () {
    $('body').bootstrapMaterialDesign();

    $('#colorize').click(function (event) {
        event.preventDefault();
        $('.colorize').each(function () {
            var self = $(this);
            self.css('color', self.data('color'));
        });
    });
});

// Default AJAX settings
/* Not available when using Slim version of jQuery
$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});*/
