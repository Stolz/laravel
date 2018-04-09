@extends('layouts.app')

@section('page.title', _('User information'))

@section('content')

    @include('me.nav')

    <p>{{ _('You are logged in!') }}</p>

    <dl>
        <dt>{{ _('Name') }}</dt>
        <dd>{{ $user->getName() }}</dd>

        <dt>{{ _('E-mail') }}</dt>
        <dd>{{ $user->getEmail() }}</dd>

        <dt>{{ _('Role') }}</dt>
        <dd>{{ $user->getRole() }}</dd>
    </dl>

@endsection
