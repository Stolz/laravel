@extends('layouts.error')

@section('error')
    {{ _('Too many requests') }}
@stop

@section('message')
    {{ _('Sorry, you exceeded the limit of requests per minute for the page.') }}
@stop

@section('solution')
    {{ _('Please wait a bit and then try again.') }}
@stop

@section('action')
    <a class="btn btn-secondary" href="{{ URL::previous() }}" role="button">
        <i class="fe fe-arrow-left"></i>
        {{ _('Go back') }}
    </a>
@stop
