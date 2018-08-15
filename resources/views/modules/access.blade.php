@extends('layouts.app')

@section('page.title', _('Access module'))

@section('main')
<div class="row justify-content-center">

    @can('list', 'App\Models\User')
    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex align-items-stretch">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title text-truncate">
                    <i class="material-icons">people</i>
                    {{ _('Users') }}
                </h5>
                <p class="card-text">{{ _('Available options for your role') }}</p>
                @can('list', 'App\Models\User')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.user.index') }}">
                        <i class="material-icons">list</i>
                        {{ _('List all') }}
                    </a>
                @endcan
                @can('create', 'App\Models\User')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.user.create') }}">
                        <i class="material-icons">add</i>
                        {{ _('Create new') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

    @can('list', 'App\Models\Role')
    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex align-items-stretch">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title text-truncate">
                    <i class="material-icons">people_outline</i>
                    {{ _('Roles') }}
                </h5>
                <p class="card-text">{{ _('Available options for your role') }}</p>
                @can('list', 'App\Models\Role')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.role.index') }}">
                        <i class="material-icons">list</i>
                        {{ _('List all') }}
                    </a>
                @endcan
                @can('create', 'App\Models\Role')
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('access.role.create') }}">
                        <i class="material-icons">add</i>
                        {{ _('Create new') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>
    @endcan

</div>
@endsection
