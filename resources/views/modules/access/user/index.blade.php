@extends('layouts.app')

@section('page.title', $title = _('Users'))

@section('side')
    @include('modules.access.user.search')
@endsection

@section('main')

    <!--TEST BEACON index-->{{-- Do not remove. Used for automatic testing --}}
    @card
        @slot('header')
            <div class="card-title">
                <i class="fe fe-user small"></i>
                {{ $title }}
            </div>
            <div class="card-options">
                @if($users->isNotEmpty())
                    <a href="#" id="colorize" title="{{ _('Colorize results') }}"><i class="fe fe-feather"></i></a>
                @endif
                <a href="#" class="toggle-side" title="{{ _('Toggle search') }}"><i class="fe fe-search"></i></a>
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

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
                        <td class="actions">@include('modules.access.user.actions')</td>
                        <td>
                            @avatar(['user' => $user, 'size' => 'sm'])
                                <span class="avatar-status {{ ($user->isDeleted()) ? 'bg-red' : 'bg-green' }}"></span>
                            @endavatar
                            {{ $user['name'] }}
                        </td>
                        <td>{{ $user['email'] }}</td>
                        <td>@colorize {{ $user['role'] }} @endcolorize</td>
                        <td title="{{ date_in_user_timezone($user['createdAt']) }}">
                            {{ $user['createdAt'] ? $user['createdAt']->diffForHumans() : null }}
                        </td>
                        <td title="{{ date_in_user_timezone($user['updatedAt']) }}">
                            {{ $user['updatedAt'] ? $user['updatedAt']->diffForHumans() : null }}
                        </td>
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

        @can('create', 'App\Models\User')
            @slot('footer')
                <a href="{{ route('access.user.create') }}" class="btn btn-success">
                    <i class="fe fe-user-plus"></i>
                    {{ _('Create new user') }}
                </a>
            @endslot
        @endcan

    @endcard

@stop
