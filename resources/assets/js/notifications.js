'use strict';

export default class Notifications {

    constructor(sse, timeout = 4600) {
        this.sse = sse; // Server-sent event stream
        this.timeout = timeout;
    }

    // Add SSE event listeners to handle notifications
    init() {
        const self = this;
        this.token = $('meta[name="csrf-token"]').attr('content');
        this.$container = $('#snackbar-container');
        this.$counter = $('.unread-notifications-counter');

        this.sse.addJsonEventListener('unreadNotificationsCount', function (event) {
           self.updateCounter(event.count);
        });

        this.sse.addJsonEventListener('notification', function (event) {
           self.show(event);
        });
    }

    // Update unread notifications counter
    updateCounter(count) {
        if(count)
            this.$counter.text(count).show();
        else
            this.$counter.text('').hide();
    }

    // Shows a notification in the snackbar
    show(notification) {
        const self = this;

        // Text of the notification
        const $message = $('<span/>')
        .attr('class', 'snackbar-content')
        .text(notification.message);

        // Button of the notification
        if(notification.action_url) {
            const type = (notification.level === 'error') ? 'danger' : notification.level;

            const $button = $('<a/>')
            .attr('href', notification.action_url)
            .attr('class', 'btn btn-' + type)
            .text(notification.action_text)
            .one('click', function (event) {
                event.preventDefault();
                $notification.removeClass('snackbar-opened');
                self.markAsRead(notification, true);
            });

            $message.append($button);
        }

        // Wrapper
        const $notification = $('<div/>')
        .attr('id', 'snackbar' + notification.id)
        .attr('class', 'snackbar snackbar-opened')
        .html($message);

        // Attach to container
        this.$container.prepend($notification);

        // Auto hide
        setTimeout(function () {
            $notification.removeClass('snackbar-opened');
        }, this.timeout);
    }

    markAsRead(notification, followUrl = false) {
        // jQuery slim version lacks AJAX support so vanilla JS is used instead
        const ajax = new XMLHttpRequest();
        ajax.open('POST', '/me/notifications');
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        ajax.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        ajax.setRequestHeader('X-CSRF-TOKEN', this.token);
        ajax.send('notification=' + notification.id);

        if (followUrl && notification.action_url) {
            ajax.onload = function() {
                window.location.replace(notification.action_url);
            };
        }
    }
}
