@extends('layouts.app')

@section('page.title', $title = _('Role details'))

@section('main')
    @card(['footerClass' => 'd-flex justify-content-between'])
        @slot('header')
            <div class="card-title">
                <i class="fe fe-shield small"></i>
                {{ $title }}
            </div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

        <div class="d-flex justify-content-between">
            <dl>
                <dt>{{ _('Name') }}</dt>
                <dd>{{ $role['name'] }}</dd>

                @if (! empty($role['description']))
                    <dt>{{ _('Description') }}</dt>
                    <dd>{{ $role['description'] }}</dd>
                @endif

                @if (! empty($role['createdAt']))
                    <dt>{{ _('Created') }}</dt>
                    <dd title="{{ date_in_user_timezone($role['createdAt']) }}">
                        {{ $role['createdAt']->diffForHumans() }}
                    </dd>
                @endif

                @if (! empty($role['updatedAt']))
                    <dt>{{ _('Updated') }}</dt>
                    <dd title="{{ date_in_user_timezone($role['updatedAt']) }}">
                        {{ $role['updatedAt']->diffForHumans() }}
                    </dd>
                @endif
            </dl>

            @if (! $role['permissions']->isEmpty())
                <dl>
                    <dt>{{ _('Permissions') }}</dt>
                    @foreach($role['permissions'] as $permission)
                        <dd title="{{ $permission['description'] }}">{{ $permission }}</dd>
                    @endforeach
                </dl>
            @endif

            @if ($users->isNotEmpty())
                <dl>
                    <dt>{{ _('Users') }}</dt>
                    @foreach($users as $user)
                        <dd>
                            @avatar(['user' => $user, 'size' => 'sm'])
                                <span class="avatar-status {{ ($user->isDeleted()) ? 'bg-red' : 'bg-green' }}"></span>
                            @endavatar
                            @can('view', $user)
                                <a href="{{ route('access.user.show', [$user['id']]) }}">{{ $user }}</a>
                            @else
                                {{ $user }}
                            @endcan
                        </dd>
                    @endforeach
                </dl>
            @endif
        </div>

        @slot('footer')
            @can('list', 'App\Models\Role')
                <a href="{{ previous_index_url(route('access.role.index')) }}" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left"></i>
                    {{ _('Return') }}
                </a>
            @endcan

            @can('update', $role)
                <a href="{{ route('access.role.edit', [$role['id']]) }}" class="btn btn-primary">
                    <i class="fe fe-edit-2"></i>
                    {{ _('Edit') }}
                </a>
            @endcan

            @can('delete', $role)
                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $role['id'] }}">
                    <i class="fe fe-user-"></i>
                    {{ _('Delete') }}
                </a>
                @deleteModelModal(['model' => $role, 'action' => route('access.role.destroy', [$role['id']])])@enddeleteModelModal
            @endcan
        @endslot
    @endcard
    <!--TEST BEACON show-->{{-- Do not remove. Used for automatic testing --}}
@stop
