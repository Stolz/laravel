@extends('layouts.app')

@section('page.title', _('User details'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
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

            @if (! empty($user['createdAt']))
                <dt>{{ _('Created') }}</dt>
                <dd title="{{ $user['createdAt'] }}">{{ $user['createdAt']->diffForHumans() }}</dd>
            @endif

            @if (! empty($user['updatedAt']))
                <dt>{{ _('Updated') }}</dt>
                <dd title="{{ $user['updatedAt'] }}">{{ $user['updatedAt']->diffForHumans() }}</dd>
            @endif
        </dl>

        <div class="row">
            @can('list', 'App\Models\User')
                <div class="col">
                    <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary btn-block">
                        <i class="material-icons">arrow_back</i>
                        {{ _('Return') }}
                    </a>
                </div>
            @endcan
            @can('update', $user)
                <div class="col">
                    <a href="{{ route('access.user.edit', [$user['id']]) }}" class="btn btn btn-primary active btn-block">
                        <i class="material-icons">edit</i>
                        {{ _('Edit') }}
                    </a>
                </div>
            @endcan
            @can('delete', $user)
                <div class="col">
                    <a href="#" class="btn btn btn-danger active btn-block" data-toggle="modal" data-target="#delete-modal-{{ $user['id'] }}">
                        <i class="material-icons">delete</i>
                        {{ _('Delete') }}
                    </a>
                </div>
                @deleteModelModal(['model' => $user, 'action' => route('access.user.destroy', [$user['id']])])
                @enddeleteModelModal
            @endcan
        </div>
    </div>
</div>
@stop
