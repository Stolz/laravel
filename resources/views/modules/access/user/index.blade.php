@extends('layouts.app')

@section('page.title', _('Users'))

@section('side')
    @include('modules.access.user.search')
@endsection

@section('main')
    @if($users->isEmpty())
        @noResultsAlert(['reset' => route('access.user.index')])
            {{ _('No users found') }}
        @endnoResultsAlert
    @else
        @table
            @slot('caption')
                @tableCaption(['paginator' => $users, 'reset' => route('access.user.index')])
                    {{ _('Showing users %d to %d out of %d') }}
                @endtableCaption
                <a id="colorize" href="javascript:void(0)">{{ _('Colorize results') }}</a>
            @endslot

            @slot('header')
            <tr>
                <th>{{ _('Actions') }}</th>
                @tableHeaders(['headers' => [
                    'name' => _('Name'),
                    'email' => _('E-mail'),
                    'role.name' => _('Role'),
                    'createdAt' => _('Created'),
                    'updatedAt' => _('Updated'),
                ]]) @endtableHeaders
            </tr>
            @endslot

            @foreach($users as $user)
            <tr>
                <td class="actions">
                    <div class="btn-group-sm" role="group" aria-label="{{ _('Actions') }}">
                        @can('view', $user)
                            <a href="{{ route('access.user.show', [$user['id']]) }}" class="btn btn-info">{{ _('View') }}</a>
                        @else
                            <a href="#" class="btn btn-info disabled">{{ _('View') }}</a>
                        @endcan
                        @can('update', $user)
                            <a href="{{ route('access.user.edit', [$user['id']]) }}" class="btn btn-primary">{{ _('Edit') }}</a>
                        @else
                            <a href="#" class="btn btn-primary disabled">{{ _('Edit') }}</a>
                        @endcan
                        @can('delete', $user)
                            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $user['id'] }}">{{ _('Delete') }}</a>
                        @else
                            <a href="#" class="btn btn-danger disabled">{{ _('Delete') }}</a>
                        @endcan
                    </div>
                </td>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>@colorize {{ $user['role'] }} @endcolorize</td>
                <td title="{{ $user['createdAt'] }}">{{ $user['createdAt'] ? $user['createdAt']->diffForHumans() : null }}</td>
                <td title="{{ $user['updatedAt'] }}">{{ $user['updatedAt'] ? $user['updatedAt']->diffForHumans() : null }}</td>
            </tr>
            @endforeach
        @endtable

        {{ $users->links('pagination') }}

        @foreach ($users as $user)
            @deleteModelModal([
                'model' => $user,
                'action' => route('access.user.destroy', [$user['id']]),
            ])
            @enddeleteModelModal
        @endforeach
    @endif

    <a class="btn btn-secondary btn-raised" href="{{ route('access.home') }}">
        <i class="material-icons">view_module</i>
        {{ _('Back to module') }}
    </a>

    @can('create', 'App\Models\User')
        <a href="{{ route('access.user.create') }}" class="btn btn-success btn-raised" >
            <i class="material-icons">add</i>
            {{ _('Create new user') }}
        </a>
    @endcan

@stop
