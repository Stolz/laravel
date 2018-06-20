@extends('layouts.app')

@section('page.title', _('Login'))

@section('content')
<form method="POST" action="{{ route('login.attempt') }}">
    @csrf

    <div class="row justify-content-md-center">
        <div class="col col-md-10 col-lg-4">

            <div class="form-group">
                <label for="email">{{ _('E-Mail') }}</label>
                <input id="email"  type="email" name="email" class="form-control @if($errors->has('email'))is-invalid @endif" placeholder="{{ _('Enter e-mail address') }}" value="{{ old('email') }}" required autofocus>
                @if($errors->has('email'))<div class="invalid-feedback">{{ $errors->first('email') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="password">{{ _('Password') }}</label>
                <input id="password" type="password" name="password" class="form-control @if($errors->has('password'))is-invalid @endif" placeholder="{{ _('Enter password') }}" required>
                @if($errors->has('password'))<div class="invalid-feedback">{{ $errors->first('password') }}</div>@endif
            </div>

            <div class="row">
                <div class="col">
                    @checkbox(['name' => 'remember', 'checked' => old('remember')])
                        {{ _('Remember me') }}
                    @endcheckbox
                </div><!--.col-->
                <div class="col text-right">
                    <a href="{{ route('password.request') }}"> {{ _('Forgot your password?') }}</a>
                </div><!--.col-->
            </div><!--.row-->

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Login') }}</button>

        </div><!--.col-->
    </div><!--.row-->

</form>
@endsection
