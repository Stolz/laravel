@extends('layouts.error')

@section('title')
    <h1 class="display-4">{{ $exception->getMessage() ?: _('Page not found') }}</h1>
@stop

@section('description')
    <p class="lead">
        {{ _('Sorry, the page you are looking for could not be found.') }}
    </p>
@stop
