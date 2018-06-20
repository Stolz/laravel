@extends('layouts.app')

@section('page.title', _('Reset password'))

@section('content')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="POST" action="{{ route('password.request') }}">
            <input type="hidden" name="token" value="{{ $token }}">
            @csrf

            <div class="form-group">
                <label for="email">{{ _('E-Mail') }}</label>
                <input id="email" type="email" name="email" class="form-control @if($errors->has('email'))is-invalid @endif" placeholder="{{ _('Enter e-mail address') }}" value="{{ $email or old('email') }}" required autofocus>
                @if($errors->has('email'))<div class="invalid-feedback">{{ $errors->first('email') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="password">{{ _('New password') }}</label>
                <input id="password" type="password" name="password" class="form-control @if($errors->has('password'))is-invalid @endif" placeholder="{{ _('Enter new password') }}" required>
                @if($errors->has('password'))<div class="invalid-feedback">{{ $errors->first('password') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ _('Repeat new password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control @if($errors->has('password_confirmation'))is-invalid @endif" placeholder="{{ _('Enter new password again') }}" required>
                @if($errors->has('password_confirmation'))<div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>@endif
            </div>

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Reset password') }}</button>
        </form>

    </div>
</div>
@endsection
