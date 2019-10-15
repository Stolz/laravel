@extends('layouts.app')

@section('page.title', $title = _('Roles'))

@section('main')
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
                        <td class="actions">@include('modules.access.role.actions')</td>
                        <td>{{ $role['name'] }}</td>
                        <td>{{ $role['description'] }}</td>
                        <td title="{{ date_in_user_timezone($role['createdAt']) }}">
                            {{ $role['createdAt'] ? $role['createdAt']->diffForHumans() : null }}
                        </td>
                        <td title="{{ date_in_user_timezone($role['updatedAt']) }}">
                            {{ $role['updatedAt'] ? $role['updatedAt']->diffForHumans() : null }}
                        </td>
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
    <!--TEST BEACON index-->{{-- Do not remove. Used for automatic testing --}}
@stop
