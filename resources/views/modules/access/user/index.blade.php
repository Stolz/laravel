@extends('layouts.app')

@section('page.title', _('Users'))

@section('main')

    @forelse ($users as $user)

        {{-- Table header --}}
        @if ($loop->first)
            <div class="table-responsive">
                <table class="table table-responsive table-striped table-hover">
                    <caption>{{ _('List of users') }}</caption>
                    <thead>
                        <tr>
                            <th>{{ _('Actions') }}</th>
                            <th>{{ _('Name') }}</th>
                            <th>{{ _('E-mail') }}</th>
                            <th>{{ _('Role') }}</th>
                        </tr>
                    </thead>
                <tbody>
        @endif

        {{-- Table body --}}
        <tr>
            <td>
                <div class="btn-group-sm" user="group" aria-label="{{ _('Actions') }}">
                    @can('view', $user)
                        <a href="{{ route('access.user.show', [$user['id']]) }}" class="btn btn-info">{{ _('View') }}</a>
                    @else
                        <a href="#" class="btn btn-info">{{ _('View') }}</a>
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
            <td>{{ $user['role'] }}</td>
        </tr>

        {{-- Table footer --}}
        @if ($loop->last)
                    </tbody>
                </table>
            </div><!--.table-responsive-->

            {{ $users->links('pagination') }}
        @endif

    @empty
        @alert(['type' => 'info'])
            {{ _('No users found') }}
        @endalert
    @endforelse

    {{-- Delete modals --}}
    @foreach ($users as $user)
        @deleteModelModal([
            'model' => $user,
            'action' => route('access.user.destroy', [$user['id']]),
        ])
        @enddeleteModelModal
    @endforeach

    @can('create', 'App\Models\User')
        <a href="{{ route('access.user.create') }}" class="btn btn-success" >{{ _('Create new user') }}</a>
    @endcan

@stop
