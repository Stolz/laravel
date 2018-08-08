// Default jQuery AJAX settings
/* Not available when using Slim version of jQuery
$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});*/

// jQuery slim version lacks AJAX support so vanilla JS is used instead
function updateUnreadNotificationsCountViaAjax(origin, destination) {
    var ajax = new XMLHttpRequest(), count = 0;

    ajax.open('GET', origin);
    ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    ajax.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    ajax.onload = function() {
        if (ajax.status === 200 && (count = parseInt(ajax.responseText)))
            destination.text(count).show();
        else
            destination.text('').hide();
    };
    ajax.send();
}

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
