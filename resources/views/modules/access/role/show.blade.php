@extends('layouts.app')

@section('page.title', _('Role'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $role['name'] }}</dd>

            @if ( ! empty($role['description']))
                <dt>{{ _('Description') }}</dt>
                <dd>{{ $role['description'] }}</dd>
            @endif

            @if ( ! empty($role['createdAt']))
                <dt>{{ _('Created') }}</dt>
                <dd>{{ $role['createdAt']->diffForHumans() }}</dd>
            @endif

            @if ( ! empty($role['updatedAt']))
                <dt>{{ _('Updated') }}</dt>
                <dd>{{ $role['updatedAt']->diffForHumans() }}</dd>
            @endif
        </dl>
    </div>
</div>
@stop
