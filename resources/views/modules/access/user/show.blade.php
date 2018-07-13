@extends('layouts.app')

@section('page.title', _('User'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $user['name'] }}</dd>

            <dt>{{ _('E-mail') }}</dt>
            <dd>{{ $user['email'] }}</dd>

            <dt>{{ _('Role') }}</dt>
            @can('view', $user['role'])
                <dd><a href="{{ route('access.role.show', [$user['role']['id']]) }}">{{ $user['role'] }}</a></dd>
            @else
                <dd>{{ $user['role'] }}</dd>
            @endcan

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
                    <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Return') }}</a>
                </div>
            @endcan
            @can('update', $user)
                <div class="col">
                    <a href="{{ route('access.user.edit', [$user['id']]) }}" class="btn btn btn-primary active btn-block">{{ _('Edit') }}</a>
                </div>
            @endcan
            @can('delete', $user)
                <div class="col">
                    <a href="#" class="btn btn btn-danger active btn-block" data-toggle="modal" data-target="#delete-modal-{{ $user['id'] }}">{{ _('Delete') }}</a>
                </div>
                @deleteModelModal(['model' => $user, 'action' => route('access.user.destroy', [$user['id']])])
                @enddeleteModelModal
            @endcan
        </div>
    </div>
</div>
@stop
