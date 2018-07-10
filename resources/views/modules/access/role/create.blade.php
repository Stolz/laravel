@extends('layouts.app')

@section('page.title', _('Create role'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('access.role.store') }}" role="form">
            @csrf
            @include('modules.access.role.form')
            <button type="submit" class="btn btn-outline-primary btn-block">{{ _('Create role') }}</button>
        </form>

    </div>
</div>
@stop
