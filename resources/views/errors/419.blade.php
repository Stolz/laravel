@extends('layouts.error')

@section('title')
    <h1 class="display-4">{{ _('Page expired') }}</h1>
@stop

@section('description')
    <p class="lead">
        {{ _('Sorry, the page has expired due to inactivity. Please refresh and try again.') }}
    </p>
@stop

@section('actions')
    <a class="btn btn-primary active" href="{{ URL::current() }}" role="button">
        <i class="material-icons">refresh</i>
        {{ _('Refresh') }}
    </a>
@stop
