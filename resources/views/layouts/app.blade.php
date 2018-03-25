@extends('layouts.base')

@section('body')
<nav>
    <a href="{{ route('home') }}">{{ _('Home') }}</a> |
    @auth
    <a href="{{ route('me') }}">{{ _('Me') }}</a> |
    <a href="{{ route('logout') }}">{{ _('Log out') }}</a>
    @else
    <a href="{{ route('login') }}">{{ _('Login') }}</a>
    @endauth
</nav>

<div id="content">
    <h1>{{ config('app.name') }}</h1>
    @include('flash-messages')
    @yield('content')
</div>
@stop
