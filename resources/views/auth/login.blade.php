@extends('layouts.app')

@section('page-title', _('Login'))
@section('page-description', _('Login page'))

@section('content')
<form method="POST" action="{{ route('do.login') }}">
    @csrf

    <label for="email">{{ _('E-Mail') }}</label>
    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if($errors->has('email'))<p class="error">{{ $errors->first('email') }}</p>@endif

    <label for="password">{{ _('Password') }}</label>
    <input type="password"  name="password" required>
    @if($errors->has('password'))<p class="error">{{ $errors->first('password') }}</p>@endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ _('Remember Me') }}
    </label>

    <input type="submit" value="{{ _('Login') }}"/>
</form>

@endsection
