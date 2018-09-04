'use strict';

export default class ServerSentEvents {

    constructor(tag, url) {
        this.tag = tag + ': ';
        this.url = url;
    }

    init() {
        if (!! window.EventSource) {
            console.info(this.tag + 'Real time events supported');
            this.sse = new EventSource(this.url);
            this.addGenericListeners();

            return true;
        }

        console.error(this.tag + 'Real time events not supported');
        return false;
    }

    addGenericListeners() {
        const sse = this.sse, tag = this.tag;

        // Listener for new connection
        this.addEventListener('open', function (event) {
            console.info(tag + 'Connection was opened');
        }, false);

        // Listener for connection close request
        this.addEventListener('close', function (event) {
            console.info(tag + 'Closing connection as per server request');
            sse.close();
        }, false);

        // Listener for connection errors
        this.addEventListener('error', function (event) {
            if (event.readyState == EventSource.CLOSED)
                console.warn(tag + 'Connection was closed');
        }, false);

        // Close the connection when the browser window is closed
        window.addEventListener('unload', function (event) {
            sse.close();
        });
        window.addEventListener('beforeunload', function (event) {
            sse.close();
        });

        // Listener for unamed events
        this.addJsonEventListener('message', function (event) {
            console.warn(tag + 'Unhandled event. Ensure sent events include a name', event);
        }, false);
    }

    addEventListener(event, callback) {
        this.sse.addEventListener(event, callback);
    }

    addJsonEventListener(event, callback) {
        this.addEventListener(event, function (event) {
            callback(JSON.parse(event.data));
        }, false);
    }
}
