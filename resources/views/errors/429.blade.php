@extends('layouts.error')

@section('title')
    <h1 class="display-4">{{ _('Too many requests') }}</h1>
@stop

@section('description')
    <p class="lead">
        {{ _('Sorry, you exceeded the limit of requests per minute for the page. Please try again soon.') }}
    </p>
@stop

@section('actions')
    <a class="btn btn-secondary active" href="{{ URL::previous() }}" role="button">
        <i class="material-icons">arrow_back</i>
        {{ _('Go back') }}
    </a>
@stop
