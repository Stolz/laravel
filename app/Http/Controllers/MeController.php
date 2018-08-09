<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\NotificationRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Show authenticated user information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showInfo(Request $request): View
    {
        return view('me.info')->withUser($request->user());
    }

    /**
     * Show form for changing authenticated user password.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showChangePasswordForm(): View
    {
        return view('me.password');
    }

    /**
     * Change the authenticated user password.
     *
     * @param  \App\Http\Requests\ChangePassword $request
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(\App\Http\Requests\ChangePassword $request, UserRepository $userRepository): RedirectResponse
    {
        $user = $request->user();

        // Validate current credentials
        $credentials = [
            'email' => $user->getEmail(),
            'password' => $request->get('password'),
        ];
        if (! $userRepository->validateCredentials($user, $credentials))
            return redirect()->back()->withErrors(['password' => _('Wrong password')]);

        // Apply changes to the user
        $user->setPassword($request->get('new_password'))->setRememberToken(str_random(60));

        // Update user in repository
        if ($userRepository->update($user)) {
            // Success
            session()->flash('success', _('Password successfully changed'));

            return redirect()->route('me');
        }

        // Error
        session()->flash('error', _('Unable to update pasword'));

        return redirect()->back();
    }

    /**
     * Show page with user notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Repositories\Contracts\NotificationRepository $notificationRepository
     * @return \Illuminate\Contracts\View\View
     */
    public function showNotifications(Request $request, NotificationRepository $notificationRepository): View
    {
        $user = $request->user();
        list($perPage, $page, $sortBy, $sortDirection) = $this->getPaginationOptionsFromRequest($request, 10);

        $notifications = $notificationRepository->paginateUser($user, $perPage, $page, $sortBy, $sortDirection);

        return view('me.notifications')->withNotifications($notifications);
    }

    /**
     * Mark user notification as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Repositories\Contracts\NotificationRepository $notificationRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markNotificationAsRead(Request $request, NotificationRepository $notificationRepository): RedirectResponse
    {
        $notification = $notificationRepository->find($request->input('notification'));

        if ($notification and $notification->isUnread() and $notification->belongsTo($request->user())) {
            $notification->setReadAt(now());
            $notificationRepository->update($notification);
        }

        return redirect()->back();
    }

    /**
     * Count the number of unread notifications.
     *
     * Called via AJAX.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Repositories\Contracts\NotificationRepository $notificationRepository
     * @return int
     */
    public function countUnreadNotifications(Request $request, NotificationRepository $notificationRepository)
    {
        return $notificationRepository->countUnread($request->user());
    }
}
