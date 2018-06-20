@extends('layouts.app')

@section('page.title', _('Login'))

@section('content')
<form method="POST" action="{{ route('login.attempt') }}">
    @csrf

    <div class="row justify-content-md-center">
        <div class="col col-md-10 col-lg-4">

            @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'required autofocus'])
                {{ _('E-Mail') }}
            @endinput

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required'])
                {{ _('Password') }}
            @endinput

            <div class="row">
                <div class="col">
                    @checkbox(['name' => 'remember', 'checked' => old('remember')])
                        {{ _('Remember me') }}
                    @endcheckbox
                </div>
                <div class="col text-right">
                    <a href="{{ route('password.request') }}"> {{ _('Forgot your password?') }}</a>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Login') }}</button>

        </div><!--.col-->
    </div><!--.row-->

</form>
@endsection
