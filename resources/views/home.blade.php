@extends('layouts.app')

@section('page.title', _('Home page'))

@section('main')

    {{-- What guest users see --}}
    @guest
    <div class="jumbotron" style="margin: 0 -15px">
        <h1 class="display-4">Welcome to {{ config('app.name') }}</h1>
        <p class="lead">Version {{ app()::VERSION }}</p>

        <hr class="my-4">

        <p>Front end built with <strong>Material Design for Bootstrap 4<strong>.</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="https://fezvrasta.github.io/bootstrap-material-design/" role="button" target="_blank">Learn more</a>
        </p>
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
