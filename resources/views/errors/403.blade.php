@extends('layouts.error')

@section('error')
    {{ $exception->getMessage() ?: _('No permission') }}
@stop

@section('message')
    {{ _('Sorry, your user is not authorized to perform this action.') }}
@stop

@section('solution')
    {{ _('Your role lacks the necessary permissions.') }}
@stop
