$(document).ready(function () {
    $('body').bootstrapMaterialDesign();

    // Default jQuery AJAX settings
    /* Disabled because jQuery slim version lacks AJAX support
    $.ajaxSetup({
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });*/

    // Disable form button after submit
    $('form').not('dont-disable').submit(function () {
        $(':input[type=submit]', $(this)).prop('disabled', true).addClass('disabled');
        return true;
    });

    // Add consistent colors
    $('#colorize').click(function (event) {
        event.preventDefault();
        $('.colorize').each(function () {
            var self = $(this);
            self.css('color', self.data('color'));
        });
    });
});

// jQuery slim version lacks AJAX support so vanilla JS is used instead
function updateUnreadNotificationsCountViaAjax(origin, destination) {
    var ajax = new XMLHttpRequest(), count = 0;

    ajax.open('GET', origin);
    ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    ajax.onload = function() {
        if (ajax.status === 200 && (count = parseInt(ajax.responseText)))
            destination.text(count).show();
        else
            destination.text('').hide();
    };
    ajax.send();
}
