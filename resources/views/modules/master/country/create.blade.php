@extends('layouts.app')

@section('page.title', _('Country'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('master.country.store') }}" role="form">
            @csrf
            @include('modules.master.country.form')
            <button type="submit" class="btn btn-success">{{ _('Create country') }}</button>
        </form>

    </div>
</div>
@stop
