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

            @if ( ! empty($user['createdAt']))
                <dt>{{ _('Created') }}</dt>
                <dd>{{ $user['createdAt']->diffForHumans() }}</dd>
            @endif

            @if ( ! empty($user['updatedAt']))
                <dt>{{ _('Updated') }}</dt>
                <dd>{{ $user['updatedAt']->diffForHumans() }}</dd>
            @endif
        </dl>
    </div>
</div>
@stop
