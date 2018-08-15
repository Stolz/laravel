@extends('layouts.error')

@section('title')
    <h1 class="display-4">{{ $exception->getMessage() ?: _('Under maintenance') }}</h1>
@stop

@section('description')
    <p class="lead">
        {{ _('Sorry but our site is currently undergoing scheduled maintenance. Please visit us again in a few minutes.') }}
    </p>
@stop

@section('actions')
    <a class="btn btn-primary active" href="{{ URL::current() }}" role="button">
        <i class="material-icons">refresh</i>
        {{ _('Retry') }}
    </a>
@stop
