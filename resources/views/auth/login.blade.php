@extends('layouts.app')

@section('page.title', _('Login'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
        <form method="post" action="{{ route('login.attempt') }}" role="form" autocomplete="off">
            @csrf

            @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'tabindex=1 required autofocus maxlength=255'])
                {{ _('E-Mail') }}
            @endinput

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'tabindex=1 required maxlength=255'])
                {{ _('Password') }}
            @endinput

            <div class="row justify-content-between">
                <div class="col">
                    @checkbox(['name' => 'remember', 'checked' => old('remember'), 'attributes' => 'tabindex=1'])
                        {{ _('Remember me') }}
                    @endcheckbox
                </div>
                <div class="col text-right">
                    <a href="{{ route('password.request') }}" tabindex="2"> {{ _('Forgot your password?') }}</a>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true" tabindex="1">
                <i class="material-icons">person</i>
                {{ _('Login') }}
            </button>
        </form>
    </div>
</div>
@endsection
