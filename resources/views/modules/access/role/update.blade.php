@extends('layouts.app')

@section('page.title', _('Update role'))

@section('main')
    <form method="post" action="{{ route('access.role.update', $role['id']) }}" role="form">
        @csrf @method('put')
        @include('modules.access.role.form')
        <button type="submit" class="btn btn-outline-primary btn-block">{{ _('Update role') }}</button>
    </form>
@stop
