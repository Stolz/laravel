@extends('layouts.error')

@section('error')
    {{ $exception->getMessage() ?: _('Unauthorized') }}
@stop

@section('message')
    {{ _('Sorry, the requested resource requires authentication.') }}
@stop

@section('solution')
    {{ _('Please log in with your credentials and try again.') }}
@stop

@section('action')
    <a class="btn btn-secondary" href="{{ URL::previous() }}" role="button">
        <i class="fe fe-arrow-left"></i>
        {{ _('Go back') }}
    </a>
    <a class="btn btn-primary" href="{{ route('login') }}" role="button">
        <i class="fe fe-log-in"></i>
        {{ _('Go to log in page') }}
    </a>
@stop
