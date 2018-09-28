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
        this.$container = $('#notifications');
        this.$indicator = $('.unread-notifications');
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
        if (count) {
            this.$counter.text(count);
            this.$indicator.show();
        } else {
            this.$indicator.hide();
            this.$counter.text('');
        }
    }

    // Shows a notification in the notifications preview dropdown
    show(notification) {
        const self = this;
        const type = (notification.level === 'error') ? 'danger' : notification.level;

        // Text of the notification
        const $message = $('<div/>')
        .attr('class', 'small text-' + type)
        .text(notification.message);

        // Button of the notification
        if (notification.action_url) {
            const $button = $('<a/>')
            .attr('href', notification.action_url)
            .attr('class', 'text-' + type)
            .text(notification.action_text)
            .one('click', function (event) {
                event.preventDefault();
                self.markAsRead(notification, true);
            })
            .wrapInner('<strong/>');

            $message.append(' ').append($button);
        }

        // Wrapper
        const $notification = $('<div/>')
        .attr('class', 'dropdown-item d-flex text-' + type)
        .html($message);

        // Attach to container
        this.$container.prepend($notification);
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
            ajax.onload = function () {
                window.location.replace(notification.action_url);
            };
        }
    }
}
