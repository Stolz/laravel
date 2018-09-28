@extends('layouts.app')

@section('page.title', $title = _('Update role'))

@section('main')
    <form method="post" action="{{ route('access.role.update', $role['id']) }}" role="form" autocomplete="off">
        @csrf @method('put')
        @card(['footerClass' => 'd-flex justify-content-between'])
            @slot('header')
                <div class="card-title">{{ $title }}</div>
                <div class="card-options">
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
                </div>
            @endslot

        @include('modules.access.role.form', ['doPermissions' => false])

        @slot('footer')
            @can('list', 'App\Models\Role')
                <a href="{{ previous_index_url(route('access.role.index')) }}" class="btn btn-outline-secondary">
                    <i class="fe fe-x"></i>
                    {{ _('Cancel') }}
                </a>
            @endcan

            <button type="submit" class="btn btn-primary">
                <i class="fe fe-edit-3"></i>
                {{ _('Update role') }}
            </button>
            @endslot
        @endcard

        @include('modules.access.role.form', ['doPermissions' => true])
    </form>
@stop
