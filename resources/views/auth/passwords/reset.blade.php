@extends('layouts.app')

@section('page.title', _('Reset password'))

@section('content')

    <form method="POST" action="{{ route('password.request') }}">
        <input type="hidden" name="token" value="{{ $token }}">
        @csrf

        <label for="email">{{ _('E-Mail') }}</label>
        <input type="email" name="email" value="{{ $email or old('email') }}" required autofocus>
        @if($errors->has('email'))<p class="error">{{ $errors->first('email') }}</p>@endif

        <label for="password">{{ _('New password') }}</label>
        <input type="password"  name="password" required>
        @if($errors->has('password'))<p class="error">{{ $errors->first('password') }}</p>@endif

        <label for="password_confirmation">{{ _('Repeat new password') }}</label>
        <input type="password"  name="password_confirmation" required>
        @if($errors->has('password_confirmation'))<p class="error">{{ $errors->first('password_confirmation') }}</p>@endif

        <input type="submit" value="{{ _('Reset password') }}"/>
    </form>

@endsection
