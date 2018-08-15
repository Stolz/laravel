@extends('layouts.app')

@section('page.title', _('Create user'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">

        <form method="post" action="{{ route('access.user.store') }}" role="form" autocomplete="off">
            @csrf
            @include('modules.access.user.form')

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'required autocomplete=new-password maxlength=255'])
                {{ _('Password') }}
                @slot('hint')
                    {{ sprintf(_('Password must be at least %d characters long'), $minPasswordLength) }}
                @endslot
            @endinput

            <div class="row">
                @can('list', 'App\Models\User')
                    <div class="col">
                        <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary btn-block">
                            <i class="material-icons">cancel</i>
                            {{ _('Cancel') }}
                        </a>
                    </div>
                @endcan
                <div class="col">
                    <button type="submit" class="btn btn-primary active btn-block">
                        <i class="material-icons">add</i>
                        {{ _('Create user') }}
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@stop
