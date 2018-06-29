@extends('layouts.app')

@section('page.title', _('Roles'))

@section('main')
    @if($roles->isEmpty())
        @alert(['type' => 'info'])
            {{ _('No roles found') }}
        @endalert
    @else
        @table
            @slot('header')
            <tr>
                <th>{{ _('Actions') }}</th>
                <th>{{ _('Name') }}</th>
                <th>{{ _('Description') }}</th>
            </tr>
            @endslot

            @foreach($roles as $role)
            <tr>
                <td>
                    <div class="btn-group-sm" role="group" aria-label="{{ _('Actions') }}">
                        @can('view', $role)
                            <a href="{{ route('access.role.show', [$role['id']]) }}" class="btn btn-info">{{ _('View') }}</a>
                        @else
                            <a href="#" class="btn btn-info">{{ _('View') }}</a>
                        @endcan
                        @can('update', $role)
                            <a href="{{ route('access.role.edit', [$role['id']]) }}" class="btn btn-primary">{{ _('Edit') }}</a>
                        @else
                            <a href="#" class="btn btn-primary disabled">{{ _('Edit') }}</a>
                        @endcan
                        @can('delete', $role)
                            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $role['id'] }}">{{ _('Delete') }}</a>
                        @else
                            <a href="#" class="btn btn-danger disabled">{{ _('Delete') }}</a>
                        @endcan
                    </div>
                </td>
                <td>{{ $role['name'] }}</td>
                <td>{{ $role['description'] }}</td>
            </tr>
            @endforeach
        @endtable

        {{ $roles->links('pagination') }}

        @foreach ($roles as $role)
            @deleteModelModal([
                'model' => $role,
                'action' => route('access.role.destroy', [$role['id']]),
            ])
            @enddeleteModelModal
        @endforeach
    @endif

    @can('create', 'App\Models\Role')
        <a href="{{ route('access.role.create') }}" class="btn btn-success" >{{ _('Create new role') }}</a>
    @endcan
@stop
