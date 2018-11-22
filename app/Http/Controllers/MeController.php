<?php

namespace App\Http\Controllers;

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
        if ($userRepository->update($user))
            // Success
            return redirect()->route('me')->with('success', _('Password successfully changed'));

        // Error
        return redirect()->back()->with('error', _('Unable to update pasword'));
    }
}
