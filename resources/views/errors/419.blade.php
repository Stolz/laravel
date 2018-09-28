@extends('layouts.error')

@section('error')
    {{ _('Page expired') }}
@stop

@section('message')
    {{ _('Sorry, the page has expired due to inactivity.') }}
@stop

@section('solution')
    {{ _('Please refresh and try again.') }}
@stop

@section('action')
    <a class="btn btn-primary active" href="{{ URL::current() }}" role="button">
        <i class="fe fe-refresh-cw"></i>
        {{ _('Refresh') }}
    </a>
@stop
