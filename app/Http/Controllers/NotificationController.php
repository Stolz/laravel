<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Instance of the service used to interact with notifications.
     *
     * @var \App\Repositories\Contracts\NotificationRepository
     */
    protected $notificationRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\NotificationRepository $notificationRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Show list of user notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        list($perPage, $page) = $this->getPaginationOptionsFromRequest($request, 10);

        // Hardcode sorting options to prevent user from playing with the parameters in the URL
        $notifications = $this->notificationRepository->paginateUser($user, $perPage, $page, 'id', 'desc');

        return view('me.notifications')->withNotifications($notifications);
    }

    /**
     * Mark a notification as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function markAsRead(Request $request)
    {
        $notification = $this->notificationRepository->find($request->input('notification'));

        $success = false;
        if ($notification and $notification->isUnread() and $notification->belongsTo($request->user())) {
            $notification->setReadAt(now());
            $success = $this->notificationRepository->update($notification);
        }

        return ($request->ajax()) ? response()->json(compact('success'), $success ? 200 : 400) : redirect()->back();
    }

    /**
     * Get notifications in real time using server-sent events (SSE).
     *
     * With initial poll frequency of 1 second and an increment in elastic poll time of 2 seconds,
     * the 60 seconds poll time limit should be reached after around 15 minutes of connection.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream(Request $request)
    {
        // Avoid uninvited guests
        if (! $request->headers->contains('accept', 'text/event-stream') and ! app()->environment('local'))
            return response('Forbidden.', 403);

        // Build an event loop for server-sent events long polling
        $user = $request->user();
        $loop = function () use ($user) {
            // Safety net to avoid keeping extremely long connections
            $closeConnectionAfter = now()->addMinutes(5);

            // Initial poll frequency in seconds
            $pollFrequency = 1;

            // Track changes to avoid sending repeated events
            $start = now();
            $lastCount = null;
            $sentNotifications = [];

            while (true) {
                // Send unread notifications count event
                $count = $this->notificationRepository->countUnread($user);
                if ($count !== $lastCount)
                    server_sent_event(['event' => 'unreadNotificationsCount', 'data' => ['count' => $lastCount = $count]]);

                // Send notification event if last unread notification is new
                if ($count and $notification = $this->notificationRepository->getLastUnread($user) and
                    $notification->isOlderThan($start) and ! in_array($notification->getId(), $sentNotifications, true)) {
                    $sentNotifications[] = $notification->getId();
                    server_sent_event(['event' => 'notification', 'data' => $notification]);
                }

                // Close the connection if we are done. Browser will try to reconnect after 'retry' miliseconds
                if ($closeConnectionAfter->isPast() or app()->isDownForMaintenance())
                    return server_sent_event(['event' => 'close', 'retry' => 60 * 1000]);

                // Elastic poll time: The more time the connections stays open, the less often we poll ...
                sleep(min($pollFrequency += 2, 60)); // ... but never wait more than a minute
            }
        };

        return $this->eventStream($loop);
    }
}
