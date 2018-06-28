@extends('layouts.app')

@section('page.title', _('Master module'))

@section('main')
<div class="row justify-content-start">

    @can('list', 'App\Models\Country')
    <div class="col-md-2 col-lg-3 col-xg-4">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">{{ _('Countries submodule') }}</h5>
                <p class="card-text">{{ _('Available options for your role') }}</p>
                @can('list', 'App\Models\Country')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('master.country.index') }}">{{ _('List all') }}</a>
                @endcan
                @can('create', 'App\Models\Country')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('master.country.create') }}">{{ _('Create new') }}</a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

</div>
@endsection
