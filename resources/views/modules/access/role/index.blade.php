@extends('layouts.app')

@section('page.title', _('Roles'))

@section('main')
    @if($roles->isEmpty())
        @noResultsAlert
            {{ _('No roles found') }}
        @endnoResultsAlert
    @else
        @table
            @slot('caption')
                @tableCaption(['paginator' => $roles])
                    {{ _('Showing roles %d to %d out of %d') }}
                @endtableCaption
            @endslot

            @slot('header')
            <tr>
                <th>{{ _('Actions') }}</th>
                @tableHeaders(['headers' => [
                    'name' => _('Name'),
                    'description' => _('Description'),
                    'createdAt' => _('Created'),
                    'updatedAt' => _('Updated'),
                ]]) @endtableHeaders
            </tr>
            @endslot

            @foreach($roles as $role)
            <tr>
                <td class="actions">
                    <div class="btn-group-sm" role="group" aria-label="{{ _('Actions') }}">
                        @can('view', $role)
                            <a href="{{ route('access.role.show', [$role['id']]) }}" class="btn btn-info">
                                <i class="material-icons">visibility</i>
                                {{ _('View') }}
                            </a>
                        @else
                            <a href="#" class="btn btn-info disabled">
                                <i class="material-icons">visibility</i>
                                {{ _('View') }}
                            </a>
                        @endcan
                        @can('update', $role)
                            <a href="{{ route('access.role.edit', [$role['id']]) }}" class="btn btn-primary">
                                <i class="material-icons">edit</i>
                                {{ _('Edit') }}
                            </a>
                        @else
                            <a href="#" class="btn btn-primary disabled">
                                <i class="material-icons">edit</i>
                                {{ _('Edit') }}
                            </a>
                        @endcan
                        @can('delete', $role)
                            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $role['id'] }}">
                                <i class="material-icons">delete</i>
                                {{ _('Delete') }}
                            </a>
                        @else
                            <a href="#" class="btn btn-danger disabled">
                                <i class="material-icons">delete</i>
                                {{ _('Delete') }}
                            </a>
                        @endcan
                    </div>
                </td>
                <td>{{ $role['name'] }}</td>
                <td>{{ $role['description'] }}</td>
                <td title="{{ $role['createdAt'] }}">{{ $role['createdAt'] ? $role['createdAt']->diffForHumans() : null }}</td>
                <td title="{{ $role['updatedAt'] }}">{{ $role['updatedAt'] ? $role['updatedAt']->diffForHumans() : null }}</td>
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

    <a class="btn btn-secondary btn-raised" href="{{ route('access.home') }}">
        <i class="material-icons">view_module</i>
        {{ _('Back to module') }}
    </a>

    @can('create', 'App\Models\Role')
        <a href="{{ route('access.role.create') }}" class="btn btn-success btn-raised" >
            <i class="material-icons">add</i>
            {{ _('Create new role') }}
        </a>
    @endcan
@stop
