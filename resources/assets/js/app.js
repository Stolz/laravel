'use strict'

window._ = require('lodash');
window.Popper = require('popper.js').default;

const ServerSentEvents = require('./serverSentEvents').default;
const Notifications = require('./notifications').default;

try {
    window.$ = window.jQuery = require('jquery');

    // Dependencies and pligins not being used have been disabled
    require('bootstrap');
    //require('bootstrap-datepicker'); // https://github.com/uxsolutions/bootstrap-datepicker",
    //require('chart.js');             // https://www.chartjs.org/
    require('requirejs/require');    // http://github.com/jrburke/r.js
    //require('select2');              // https://select2.org/
    require('selectize');            // Selectize http://selectize.github.io/selectize.js/
    //require('sparkline');            // https://github.com/shiwano/sparkline
    require('tabler-ui/dist/assets/js/dashboard'); // https://tabler.github.io
    //require('tabler-ui/src/assets/plugins/charts-c3/js/c3.min');
    //require('tabler-ui/src/assets/plugins/fullcalendar/js/fullcalendar.min');
    //require('tabler-ui/src/assets/plugins/input-mask/js/jquery.mask.min');
    //require('tabler-ui/src/assets/plugins/prismjs/js/prism.pack');
    //require('vector-map');          // http://jvectormap.com
} catch (e) {}

/* Default jQuery AJAX settings. Disabled because we are currently using jQuery Slim version which lacks AJAX support
$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});*/

// Run once the page DOM is ready
$(function () {

    // Initialize jQuery plugins
    $('select').selectize({});
    //$('.js-datepicker').datepicker({todayHighlight: true, autoclose: true});
    //$('.js-select2').select2({allowClear: true, dropdownAutoWidth: true, theme: 'bootstrap'});

    // Real time notifications via server-sent events
    if (typeof serverSentEventsUrl != 'undefined') {
        // Check if SSE are supported
        const sse = new ServerSentEvents('SSE', serverSentEventsUrl);
        if (sse.init()) {
            // Set notification listeners
            const notifications = window.notifications = new Notifications(sse);
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

    // Toggle side section
    $('.toggle-side,.show-side,.hide-side').click(function (event) {
        event.preventDefault();
        const $target = $(event.target);
        if ($target.hasClass('show-side'))
            $('#side').show();
        else if ($target.hasClass('hide-side'))
            $('#side').hide();
        else
            $('#side').toggle();
    });
});
