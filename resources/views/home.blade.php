@extends('layouts.app')

@section('page.title', _('Home page'))

@section('main')

    @auth
        <i class="h2 float-left fe fe-corner-left-up d-none d-lg-block mx-1"></i>
        <i class="h2 float-right fe fe-corner-right-up d-lg-none mx-1"></i>
        <h2 class="text-muted">{{ _('Please use the menu to navigate') }}</h2>
    @else
        <div class="text-center">
            <div class="display-3 text-muted mt-md-9">
                {{ _('Welcome to') }}
                <h1 class="display-2">{{ config('app.name') }}</h1>
            </div>

            <div class="h2">{{ _('Please log in to continue') }}</div>

            <p>
                <a class="btn btn-primary my-5" href="{{ route('login') }}" role="button">
                    <i class="fe fe-log-in mr-2"></i>
                    {{ _('Log in') }}
                </a>
            </p>
        </div>
    @endauth

@endsection
