@extends('layouts.error')

@section('error')
    {{ $exception->getMessage() ?: _('Under maintenance') }}
@stop

@section('message')
    {{ _('Sorry, our site is currently undergoing scheduled maintenance.') }}
@stop

@section('solution')
    {{ _('Please visit us again in a few minutes.') }}
@stop

@section('action')
    <a class="btn btn-primary" href="{{ URL::current() }}" role="button">
        <i class="fe fe-refresh-cw"></i>
        {{ _('Retry') }}
    </a>
@stop
