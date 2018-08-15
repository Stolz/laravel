@extends('layouts.app')

@section('page.title', _('Update user'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">

        <form method="post" action="{{ route('access.user.update', $user['id']) }}" role="form" autocomplete="off">
            @csrf @method('put')
            @include('modules.access.user.form')

            @input(['type' => 'password', 'name' => 'password', 'attributes' => 'autocomplete=new-password maxlength=255'])
                {{ _('Password') }}
                @slot('help')
                    {{ _("Leave it blank if you don't want to update the password") }}
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
                        <i class="material-icons">save</i>
                        {{ _('Update user') }}
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@stop
