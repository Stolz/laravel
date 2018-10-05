@extends('layouts.app')

@section('page.title', $title = _('Reset password'))

@section('main')
<div class="row mt-md-7">
    <div class="col col-login mx-auto">

        @card
            <form method="post" action="{{ route('password.request') }}" role="form" autocomplete="off">
                <input type="hidden" name="token" value="{{ $token }}">
                @csrf

                <div class="card-title">{{ $title }}</div>

                @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'icon' => 'fe fe-at-sign', 'attributes' => 'required autofocus maxlength=255'])
                    {{ _('E-Mail') }}
                @endinput

                @input(['type' => 'password', 'name' => 'password', 'icon' => 'fe fe-lock', 'attributes' => 'required maxlength=255'])
                    {{ _('New password') }}
                    @slot('help')
                        {{ sprintf(_('Your password must be at least %d characters long'), \App\Models\User::MIN_PASSWORD_LENGTH) }}
                    @endslot
                @endinput

                @input(['type' => 'password', 'name' => 'password_confirmation', 'icon' => 'fe fe-repeat', 'attributes' => 'required maxlength=255'])
                    {{ _('Repeat new password') }}
                @endinput

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block" role="button">
                        {{ _('Reset password') }}
                    </button>
                </div>
            </form>
        @endcard
    </div>
</div>
@endsection
