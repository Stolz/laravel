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

    // Real time notifications via server-sent events
    if (serverSentEventsUrl) {
        if (!!window.EventSource) {
            console.log('Server-sent Events (SSE) supported')
            var source = new EventSource(serverSentEventsUrl);

            // Listener for new connection
            source.addEventListener('open', function(event) {
                console.log('SSE connection was opened');
            }, false);

            // Listener for connection close request
            source.addEventListener('close', function(event) {
                console.log('Closing SSE connection as per server request');
                source.close();
            }, false);

            // Listener for connection errors
            source.addEventListener('error', function(event) {
                if (event.readyState == EventSource.CLOSED)
                    console.log('SSE connection was closed');
            }, false);

            // Listeners for closing the connection when the browser window is closed
            window.addEventListener('unload', function(event) {
                source.close();
            });
            window.addEventListener('beforeunload', function(event) {
                source.close();
            });

            // Listener for unnamed events
            source.addEventListener('message', function(event) {
                var data = JSON.parse(event.data);
                console.log('SSE unnamed event', data);
            }, false);

            // Listener for unread notifications count
            var $unreadNotificationsCounter = $('.unread-notifications-counter');
            source.addEventListener('unreadNotificationsCount', function(event) {
                var data = JSON.parse(event.data);
                if(data.count)
                    $unreadNotificationsCounter.text(data.count).show();
                else
                    $unreadNotificationsCounter.text('').hide();
            }, false);
        }
        else {
            console.error('Server-sent Events not supported');
        }
    }
});

