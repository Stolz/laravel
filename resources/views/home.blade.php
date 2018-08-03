@extends('layouts.app')

@section('page.title', _('Home page'))

@section('main')

    {{-- What guest users see --}}
    @guest
    <div class="jumbotron" style="margin: 0 -15px">
        <div class="row justify-content-center">
            <div class="col-sm-11 col-md-9 col-lg-7 col-xl-5">
                <h1 class="display-4">{{ sprintf(_('Welcome to %s'), config('app.name')) }}</h1>
                @if(! app()->environment('production'))
                    <span class="badge badge-secondary">{{ sprintf(_('%s environment'), app()->environment()) }}</span>
                @endif

                <hr>

                <p class="lead">{{ _('Please log in to continue') }}</p>
                <a class="btn btn-primary active" href="{{ route('login') }}" role="button">{{ _('Login') }}</a>
            </div>
        </div>
    </div>
    @endguest

    {{-- What authenticated users see --}}
    @auth
    <div class="row justify-content-center">

        @can('access', 'module')
        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex align-items-stretch">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title text-truncate">{{ _('Access module') }}</h5>
                    <p class="card-text">{{ _('Manage users, roles and permissions') }}</p>
                    <a class="btn btn-primary btn-raised" href="{{ route('access.home') }}">{{ _('Go to module') }}</a>
                </div>
            </div>
        </div>
        @endcan

        @can('master', 'module')
        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 d-flex align-items-stretch">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title text-truncate">{{ _('Master module') }}</h5>
                    <p class="card-text">{{ _('Manage countries') }}</p>
                    <a class="btn btn-primary btn-raised" href="{{ route('master.home') }}">{{ _('Go to module') }}</a>
                </div>
            </div>
        </div>
        @endcan

    </div>
    @endauth

@endsection
