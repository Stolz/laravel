@extends('layouts.base')

@section('page.title', sprintf(_('Error %d'), $exception->getCode() ?: $exception->getStatusCode()))

@prepend('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endprepend

@prepend('js')
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
@endprepend

@section('body')
<div id="container" class="bmd-layout-container bmd-drawer-f-l">

    <header id="header" class="bmd-layout-header">
        @include('top')
    </header>

    <main id="main" class="bmd-layout-content container-fluid">
        <div class="jumbotron">
            <div class="row justify-content-center">
                <div class="col-sm-11 col-md-10 col-lg-9 col-xl-8">

                    @section('title')
                        <h1 class="display-4">{{ $exception->getMessage() ?: _('Unknown error') }}</h1>
                    @show

                    <hr class="my-4">

                    @section('description')
                        <p class="lead">
                            {{ _('Something went wrong while we were processing your request') }}.
                            {{ _('We are sorry about this and will work hard to get this resolved as soon as possible') }}.
                        </p>
                    @show

                    @section('actions')
                        <a class="btn btn-secondary active" href="{{ URL::previous() }}" role="button">
                            <i class="material-icons">arrow_back</i>
                            {{ _('Go back') }}
                        </a>
                        <a class="btn btn-primary active" href="{{ route('home') }}" role="button">
                            <i class="material-icons">home</i>
                            {{ _('Go to home page') }}
                        </a>
                    @show

                </div>
            </div>
        </div>
    </main>

</div>
@stop
