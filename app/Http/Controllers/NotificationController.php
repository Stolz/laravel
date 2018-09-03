<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 10);

        $notifications = $this->notificationRepository->paginateUser($user, $perPage, $page, $sortBy, $sortDirection);

        return view('me.notifications')->withNotifications($notifications);
    }

    /**
     * Mark a notification as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request): RedirectResponse
    {
        $notification = $this->notificationRepository->find($request->input('notification'));

        if ($notification and $notification->isUnread() and $notification->belongsTo($request->user())) {
            $notification->setReadAt(now());
            $this->notificationRepository->update($notification);
        }

        return redirect()->back();
    }

    /**
     * Get notifications in real time using server-sent events (SSE).
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
            $closeConnectionAfter = now()->addMinutes(20);

            // Initial poll frequency in seconds
            $pollFrequency = 3;

            while (true) {
                // Send unread notifications count event
                $count = $this->notificationRepository->countUnread($user);
                server_sent_event(['event' => 'unreadNotificationsCount', 'data' => ['count' => $count]]);

                // Check if we are done
                if ($closeConnectionAfter->isPast())
                    return server_sent_event(['event' => 'close', 'data' => _('user seems idle')]);

                // Elastic poll time: The more time the connections stays open, the less often we poll
                sleep(min($pollFrequency++, 60)); // Never wait more than a minute
            }
        };

        return $this->eventStream($loop);
    }
}
