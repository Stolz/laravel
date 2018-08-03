@extends('layouts.error')

@section('title')
    <h1 class="display-4">{{ $exception->getMessage() ?: _('No permission') }}</h1>
@stop

@section('description')
    <p class="lead">
        {{ _('Sorry but your user is not authorized to perform this action.') }}
    </p>
@stop

