@extends('layouts.app')

@section('page.title', _('Profile'))

@section('main')
<div class="row">
    <div class="col col-login mx-auto">
        @card
            @slot('header')
                <h3 class="card-title">{{ _('Your profile') }}</h3>
                <div class="card-options">
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
                </div>
            @endslot

            <div class="d-flex justify-content-between">
                <dl>
                    <dt>{{ _('Name') }}</dt>
                    <dd>{{ $user['name'] }}</dd>

                    <dt>{{ _('E-mail') }}</dt>
                    <dd>{{ $user['email'] }}</dd>

                    <dt>{{ _('Role') }}</dt>
                    <dd>{{ $user['role'] }}</dd>
                </dl>

                <div class="text-center">
                    @avatar(['user' => $user, 'size' => 'xxl'])@endavatar<br/>
                    <a href="https://www.gravatar.com" target="_blank" class="btn btn-link btn-sm">
                        <i class="fe fe-external-link"></i>
                        {{ _('Change avatar' )}}
                    </a>
                </div>
            </div>
        @endcard
    </div><!--.col-->
</div><!--.row-->
@endsection
