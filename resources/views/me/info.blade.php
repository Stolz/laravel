@extends('layouts.app')

@section('page.title', _('User information'))

@section('content')

    <p>{{ _('You are logged in!') }}</p>

    <dl>
        <dt>{{ _('Name') }}</dt>
        <dd>{{ $user->getName() }}</dd>

        <dt>{{ _('E-mail') }}</dt>
        <dd>{{ $user->getEmail() }}</dd>
    </dl>

    <a href="{{ route('me.password') }}">{{ _('Change password') }}</a>

@endsection
