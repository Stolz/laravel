'use strict';

export default class ServerSentEvents {

    constructor(label, url) {
        this.label = label; // Name of the stream
        this.url = url;     // Url of the stream
    }

    init() {
        if (!! window.EventSource) {
            console.info(this.label + ': Real time events supported');
            this.sse = new EventSource(this.url);
            this.addGenericListeners();

            return true;
        }

        console.error(this.label + ': Real time events not supported');
        return false;
    }

    addGenericListeners() {
        const sse = this.sse;
        const label = this.label;

        // Listener for new connection
        sse.addEventListener('open', function (event) {
            console.info(label + ': Connection was opened');
        }, false);

        // Listener for unamed events
        sse.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);
            console.warn(label + ': Unhandled event. Ensure sent events include a name', data);
        }, false);

        // Listener for connection close request
        sse.addEventListener('close', function (event) {
            console.info(label + ': Closing connection as per server request');
            sse.close();
        }, false);

        // Listener for connection errors
        sse.addEventListener('error', function (event) {
            if (event.readyState == EventSource.CLOSED)
                console.warn(label + ': Connection was closed');
        }, false);

        // Close the connection when the browser window is closed
        window.addEventListener('unload', function (event) {
            sse.close();
        });
        window.addEventListener('beforeunload', function (event) {
            sse.close();
        });
    }

    addEventHandler(event, handler) {
        this.sse.addEventListener(event, handler, false);
    }
}



