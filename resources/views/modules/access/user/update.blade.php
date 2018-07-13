@extends('layouts.app')

@section('page.title', _('Update user'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        <form method="post" action="{{ route('access.user.update', $user['id']) }}" role="form">
            @csrf @method('put')
            @include('modules.access.user.form')

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'autocomplete=off'])
                {{ _('Password') }}
                @slot('help')
                    {{ _("Leave it blank if you don't want to update the password") }}
                @endslot
            @endinput

            <div class="row">
                @can('list', 'App\Models\User')
                    <div class="col">
                        <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Cancel') }}</a>
                    </div>
                @endcan
                <div class="col">
                    <button type="submit" class="btn btn-primary active btn-block">{{ _('Update user') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>
@stop
