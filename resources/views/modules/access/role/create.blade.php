@extends('layouts.app')

@section('page.title', _('Role'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('access.role.store') }}" role="form">
            @csrf
            @include('modules.access.role.form')
            <button type="submit" class="btn btn-success">{{ _('Create role') }}</button>
        </form>

    </div>
</div>
@stop
