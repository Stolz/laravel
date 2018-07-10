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

            <button type="submit" class="btn btn-outline-primary btn-block">{{ _('Update user') }}</button>
        </form>

    </div>
</div>
@stop
