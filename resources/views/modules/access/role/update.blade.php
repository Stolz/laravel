@extends('layouts.app')

@section('page.title', _('Update role'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('access.role.update', $role['id']) }}" role="form">
            @csrf @method('put')
            @include('modules.access.role.form')
            <button type="submit" class="btn btn-primary">{{ _('Update role') }}</button>
        </form>

    </div>
</div>
@stop
