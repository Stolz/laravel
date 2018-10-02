@extends('layouts.app')

@section('page.title', $title = _('Change password'))

@section('main')
<div class="row">
    <div class="col col-login mx-auto">
        @card
            <h3 class="card-title">{{ $title }}</h3>

            <form method="post" action="{{ route('me.password.change') }}" role="form" autocomplete="off">
                @csrf

                @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required autofocus autocomplete=new-password maxlength=255'])
                    {{ _('Current password') }}
                @endinput

                @input(['type' => 'password', 'name' => 'new_password', 'attributes' => 'required autocomplete=new-password maxlength=255'])
                    {{ _('New password') }}
                    @slot('help')
                        {{ sprintf(_('Your password must be at least %d characters long'), \App\Models\User::MIN_PASSWORD_LENGTH) }}
                    @endslot
                @endinput

                @input(['type' => 'password', 'name' => 'new_password_confirmation', 'attributes' => 'required autocomplete=new-password maxlength=255'])
                    {{ _('Repeat new password') }}
                @endinput

                <div class="form-footer">
                    <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">
                        <i class="fe fe-lock mr-2"></i>
                        {{ _('Change password') }}
                    </button>
                </div>
            </form>

        @endcard
    </div><!--.col-->
</div><!--.row-->
@endsection
