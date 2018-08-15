@extends('layouts.app')

@section('page.title', _('Create role'))

@section('main')
    <form method="post" action="{{ route('access.role.store') }}" role="form" autocomplete="off">
        @csrf
        @include('modules.access.role.form')

        <div class="row">
            @can('list', 'App\Models\Role')
                <div class="col">
                    <a href="{{ previous_index_url(route('access.role.index')) }}" class="btn btn-outline-secondary btn-block">
                        <i class="material-icons">cancel</i>
                        {{ _('Cancel') }}
                    </a>
                </div>
            @endcan
            <div class="col">
                <button type="submit" class="btn btn-primary active btn-block">
                    <i class="material-icons">add</i>
                    {{ _('Create role') }}
                </button>
            </div>
        </div>
    </form>
@stop
