@extends('layouts.app')

@section('page.title', _('Create role'))

@section('main')
    <form method="post" action="{{ route('access.role.store') }}" role="form">
        @csrf
        @include('modules.access.role.form')

        <div class="row">
            @can('list', 'App\Models\Role')
                <div class="col">
                    <a href="{{ previous_index_url(route('access.role.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Cancel') }}</a>
                </div>
            @endcan
            <div class="col">
                <button type="submit" class="btn btn-primary active btn-block">{{ _('Create role') }}</button>
            </div>
        </div>
    </form>
@stop
