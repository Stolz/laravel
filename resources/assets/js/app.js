'use strict'

require('./vendor');
const ServerSentEvents = require('./serverSentEvents').default;
const Notifications = require('./notifications').default;

/* Default jQuery AJAX settings. Disabled because we are currently using jQuery Slim version which lacks AJAX support
$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});*/

// Run once the page DOM is ready
$(function () {

    // Initialize Twitter Bootstrap
    $('body').bootstrapMaterialDesign();

    // Real time notifications via server-sent events
    if (typeof serverSentEventsUrl != 'undefined') {
        // Check if SSE are supported
        const sse = new ServerSentEvents('SSE', serverSentEventsUrl);
        if(sse.init()) {
            // Set notification listeners
            const notifications = new Notifications(sse);
            notifications.init();
        }
    }

    // Disable form button after submitting
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
