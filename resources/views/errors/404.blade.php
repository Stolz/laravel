@extends('layouts.error')

@section('error')
    {{ $exception->getMessage() ?: _('Page not found') }}
@stop

@section('message')
    {{ _('Sorry, the requested resource could not be found.') }}
@stop

@section('solution')
    {{ _('The link you clicked may be broken or the resource may have been removed.') }}
@stop
