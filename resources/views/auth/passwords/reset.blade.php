@extends('layouts.app')

@section('page.title', _('Reset password'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">

        <form method="post" action="{{ route('password.request') }}" role="form" autocomplete="off">
            <input type="hidden" name="token" value="{{ $token }}">
            @csrf

            @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'required autofocus maxlength=255'])
                {{ _('E-Mail') }}
            @endinput

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required maxlength=255'])
                {{ _('New password') }}
                @slot('hint')
                    {{ sprintf(_('Your password must be at least %d characters long'), \App\Models\User::MIN_PASSWORD_LENGTH) }}
                @endslot
            @endinput

            @input(['type' => 'password', 'name' => 'password_confirmation', 'attributes' => 'required maxlength=255'])
                {{ _('Repeat new password') }}
             @endinput

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">
                <i class="material-icons">settings_backup_restore</i>
                {{ _('Reset password') }}
            </button>
        </form>

    </div>
</div>
@endsection
