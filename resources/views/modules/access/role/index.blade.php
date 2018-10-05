@extends('layouts.app')

@section('page.title', $title = _('Roles'))

@section('main')

    <!--TEST BEACON index-->{{-- Do not remove. Used for automatic testing --}}
    @card
        @slot('header')
            <div class="card-title">
                <i class="fe fe-shield small"></i>
                {{ $title }}
            </div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

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
                            <div class="btn-group" role="group" aria-label="{{ _('Actions') }}">
                                @can('view', $role)
                                    <a href="{{ route('access.role.show', [$role['id']]) }}" class="btn btn-info">
                                        <i class="fe fe-eye"></i>
                                        {{ _('View') }}
                                    </a>
                                @else
                                    <a href="#" class="btn btn-info disabled">
                                        <i class="fe fe-eye"></i>
                                        {{ _('View') }}
                                    </a>
                                @endcan

                                @can('update', $role)
                                    <a href="{{ route('access.role.edit', [$role['id']]) }}" class="btn btn-primary">
                                        <i class="fe fe-edit-2"></i>
                                        {{ _('Edit') }}
                                    </a>
                                @else
                                    <a href="#" class="btn btn-primary disabled">
                                        <i class="fe fe-edit-2"></i>
                                        {{ _('Edit') }}
                                    </a>
                                @endcan

                                @can('delete', $role)
                                    <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $role['id'] }}">
                                        <i class="fe fe-trash-2"></i>
                                        {{ _('Delete') }}
                                    </a>
                                @else
                                    <a href="#" class="btn btn-danger disabled">
                                        <i class="fe fe-trash-2"></i>
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

        @can('create', 'App\Models\Role')
            @slot('footer')
                <a href="{{ route('access.role.create') }}" class="btn btn-success">
                    <i class="fe fe-plus"></i>
                    {{ _('Create new role') }}
                </a>
            @endslot
        @endcan

    @endcard

@stop
