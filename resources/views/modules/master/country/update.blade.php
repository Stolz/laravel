@extends('layouts.app')

@section('page.title', _('Edit country'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('master.country.update', $country['id']) }}" role="form">
            @csrf @method('put')
            @include('modules.master.country.form')
            <button type="submit" class="btn btn-primary">{{ _('Update country') }}</button>
        </form>

    </div>
</div>
@stop
