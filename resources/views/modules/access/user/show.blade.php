@extends('layouts.app')

@section('page.title', $title = _('User details'))

@section('main')
    @card(['footerClass' => 'd-flex justify-content-between'])
        @slot('header')
            <div class="card-title">
                <i class="fe fe-user small"></i>
                {{ $title }}
            </div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

        <div class="d-flex justify-content-between">
            <dl>
                <dt>{{ _('Name') }}</dt>
                <dd>{{ $user['name'] }}</dd>

                <dt>{{ _('E-mail') }}</dt>
                <dd>{{ $user['email'] }}</dd>

                <dt>{{ _('Role') }}</dt>
                <dd>
                    @can('view', $user['role'])
                        <a href="{{ route('access.role.show', [$user['role']['id']]) }}">{{ $user['role'] }}</a>
                    @else
                        {{ $user['role'] }}
                    @endcan
                </dd>
            </dl>

            <dl>
                @if (! empty($user['createdAt']))
                    <dt>{{ _('Created') }}</dt>
                    <dd title="{{ $user['createdAt'] }}">{{ $user['createdAt']->diffForHumans() }}</dd>
                @endif

                @if (! empty($user['updatedAt']))
                    <dt>{{ _('Updated') }}</dt>
                    <dd title="{{ $user['updatedAt'] }}">{{ $user['updatedAt']->diffForHumans() }}</dd>
                @endif
            </dl>

            <div class="float-right">
                @avatar(['user' => $user, 'size' => 'xxl'])@endavatar
            </div>
        </div>

        @slot('footer')
            @can('list', 'App\Models\User')
                <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left"></i>
                    {{ _('Return') }}
                </a>
            @endcan

            @can('update', $user)
                <a href="{{ route('access.user.edit', [$user['id']]) }}" class="btn btn-primary">
                    <i class="fe fe-edit-2"></i>
                    {{ _('Edit') }}
                </a>
            @endcan

            @can('delete', $user)
                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $user['id'] }}">
                    <i class="fe fe-user-x"></i>
                    {{ _('Delete') }}
                </a>
                @deleteModelModal(['model' => $user, 'action' => route('access.user.destroy', [$user['id']])])@enddeleteModelModal
            @endcan
        @endslot
    @endcard
@stop
