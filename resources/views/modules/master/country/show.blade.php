@extends('layouts.app')

@section('page.title', _('Country'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $country['name'] }}</dd>
        </dl>
    </div>
</div>
@stop
