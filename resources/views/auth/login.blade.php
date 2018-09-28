@extends('layouts.app')

@section('page.title', $title = _('Log in'))

@section('main')
<div class="row mt-md-7">
    <div class="col col-login mx-auto">
        @card
            <form method="post" action="{{ route('login.attempt') }}" role="form" autocomplete="off">
                @csrf
                <div class="card-title">{{ $title }}</div>

                @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'required autofocus maxlength=255'])
                    {{ _('E-Mail') }}
                @endinput

                @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required maxlength=255'])
                    {{ _('Password') }}
                @endinput

                @checkbox(['name' => 'remember', 'checked' => old('remember')])
                    {{ _('Remember me') }}
                @endcheckbox

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block" role="button">
                        <i class="fe fe-log-in mr-1"></i>
                        {{ _('Login') }}
                    </button>
                </div>
            </form>
        @endcard

        <div class="text-center">
            <a href="{{ route('password.request') }}"> {{ _('Forgot your password?') }}</a>
        </div>
    </div>
</div>
@endsection
