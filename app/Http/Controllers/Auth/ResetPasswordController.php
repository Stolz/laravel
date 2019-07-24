<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ResetPasswordController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        ResetsPasswords::broker insteadof SendsPasswordResetEmails;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    /**
     * Instance of the service used to interact with users.
     *
     * @var \App\Repositories\Contracts\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\UserRepository $userRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @return string
     */
    public function redirectTo(): string
    {
        return route('me');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        // Apply changes to the user
        $user->setPassword($password)->setRememberToken(str_random(60));

        // Update user in repository
        if ($this->userRepository->update($user)) {
            event(new \Illuminate\Auth\Events\PasswordReset($user));
            $this->guard()->login($user);
        }
    }
}
