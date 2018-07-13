@extends('layouts.app')

@section('page.title', _('Create user'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('access.user.store') }}" role="form">
            @csrf
            @include('modules.access.user.form')

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required autocomplete=off'])
                {{ _('Password') }}
                @slot('hint')
                    {{ sprintf(_('Password must be at least %d characters long'), $minPasswordLength) }}
                @endslot
            @endinput

            <div class="row">
                @can('list', 'App\Models\User')
                    <div class="col">
                        <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Cancel') }}</a>
                    </div>
                @endcan
                <div class="col">
                    <button type="submit" class="btn btn-primary active btn-block">{{ _('Create user') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>
@stop
