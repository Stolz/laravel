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

            <button type="submit" class="btn btn-outline-primary btn-block btn-raised ">{{ _('Create user') }}</button>
        </form>

    </div>
</div>
@stop
