@extends('layouts.app')

@section('page.title', _('Change password'))

@section('content')

    <form method="POST" action="{{ route('me.password.change') }}">
        @csrf

        <label for="password">{{ _('Current password') }}</label>
        <input type="password"  name="password" required>
        @if($errors->has('password'))<p class="error">{{ $errors->first('password') }}</p>@endif

        <label for="new_password">{{ _('New password') }}</label>
        <input type="password"  name="new_password" required>
        @if($errors->has('new_password'))<p class="error">{{ $errors->first('new_password') }}</p>@endif

        <label for="new_password_confirmation">{{ _('Repeat new password') }}</label>
        <input type="password"  name="new_password_confirmation" required>
        @if($errors->has('new_password_confirmation'))<p class="error">{{ $errors->first('new_password_confirmation') }}</p>@endif

        <input type="submit" value="{{ _('Change password') }}"/>
    </form>

@endsection
