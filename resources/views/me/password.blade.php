@extends('layouts.app')

@section('page.title', _('Change password'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
        <form method="post" action="{{ route('me.password.change') }}" role="form" autocomplete="off">
            @csrf

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required autofocus autocomplete=new-password maxlength=255'])
                {{ _('Current password') }}
            @endinput

            @input(['type' => 'password', 'name' => 'new_password', 'attributes' => 'required autocomplete=new-password maxlength=255'])
                {{ _('New password') }}
                @slot('hint')
                    {{ sprintf(_('Your password must be at least %d characters long'), \App\Models\User::MIN_PASSWORD_LENGTH) }}
                @endslot
            @endinput

            @input(['type' => 'password', 'name' => 'new_password_confirmation', 'attributes' => 'required autocomplete=new-password maxlength=255'])
                {{ _('Repeat new password') }}
            @endinput

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Change password') }}</button>
        </form>
    </div>
</div>
@endsection
