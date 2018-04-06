@extends('layouts.app')

@section('page.title', _('Send password reset link'))

@section('content')

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">{{ _('E-Mail') }}</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        @if($errors->has('email'))<p class="error">{{ $errors->first('email') }}</p>@endif

        <input type="submit" value="{{ _('Send password reset link') }}"/>
    </form>

    @if(session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif

@endsection
