@extends('layouts.app')

@section('page.title', _('Role'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $role['name'] }}</dd>

            @if (! empty($role['description']))
                <dt>{{ _('Description') }}</dt>
                <dd>{{ $role['description'] }}</dd>
            @endif

            @if (! empty($role['createdAt']))
                <dt>{{ _('Created') }}</dt>
                <dd title="{{ $role['createdAt'] }}">{{ $role['createdAt']->diffForHumans() }}</dd>
            @endif

            @if (! empty($role['updatedAt']))
                <dt>{{ _('Updated') }}</dt>
                <dd title="{{ $role['updatedAt'] }}">{{ $role['updatedAt']->diffForHumans() }}</dd>
            @endif

            @if ($role['permissions']->count())
                <dt>{{ _('Permissions') }}</dt>
                @foreach($role['permissions'] as $permission)
                    <dd title="$permission['description']">{{ $permission }}</dd>
                @endforeach
            @endif
        </dl>

        <div class="row">
            @can('list', 'App\Models\Role')
                <div class="col">
                    <a href="{{ previous_index_url(route('access.role.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Return') }}</a>
                </div>
            @endcan
            @can('update', $role)
                <div class="col">
                    <a href="{{ route('access.role.edit', [$role['id']]) }}" class="btn btn btn-primary active btn-block">{{ _('Edit') }}</a>
                </div>
            @endcan
            @can('delete', $role)
                <div class="col">
                    <a href="#" class="btn btn btn-danger active btn-block" data-toggle="modal" data-target="#delete-modal-{{ $role['id'] }}">{{ _('Delete') }}</a>
                </div>
                @deleteModelModal(['model' => $role, 'action' => route('access.role.destroy', [$role['id']])])
                @enddeleteModelModal
            @endcan
        </div>
    </div>
</div>
@stop
