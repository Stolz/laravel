@extends('layouts.app')

@section('page.title', _('Access module'))

@section('main')
<div class="row justify-content-start">

    @can('list', 'App\Models\User')
    <div class="col-md-2 col-lg-3 col-xg-4">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">{{ _('Users submodule') }}</h5>
                <p class="card-text">{{ _('Available options for your role') }}</p>
                @can('list', 'App\Models\User')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.user.index') }}">{{ _('List all') }}</a>
                @endcan
                @can('create', 'App\Models\User')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.user.create') }}">{{ _('Create new') }}</a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    @can('list', 'App\Models\Role')
    <div class="col-md-2 col-lg-3 col-xg-4">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">{{ _('Roles submodule') }}</h5>
                <p class="card-text">{{ _('Available options for your role') }}</p>
                @can('list', 'App\Models\Role')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.role.index') }}">{{ _('List all') }}</a>
                @endcan
                @can('create', 'App\Models\Role')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.role.create') }}">{{ _('Create new') }}</a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

</div>
@endsection
