@extends('layouts.base')

@section('page.title', sprintf(_('Error %d'), $exception->getCode() ?: $exception->getStatusCode()))

@prepend('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endprepend

@prepend('js')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
@endprepend

@section('body')
<div class="page">
    <div class="page-content">
        <div class="container text-center">
            <h1 class="display-1 text-muted mb-5">
                @section('error')
                    {{ $exception->getMessage() ?: _('Unknown error') }}
                @show
            </h1>

            <div class="h2 mb-3">
                @section('message')
                    {{ _('Something went wrong while we were processing your request.') }}<br>
                @show
            </div>

            <div class="h4 text-muted font-weight-normal mb-7">
                @section('solution')
                    {{ _('We are sorry about this and we will work hard to get this resolved as soon as possible.') }}
                @show
            </div>

            @section('action')
                <a class="btn btn-secondary" href="{{ URL::previous() }}" role="button">
                    <i class="fe fe-arrow-left"></i>
                    {{ _('Go back') }}
                </a>
                <a class="btn btn-primary" href="{{ route('home') }}" role="button">
                    <i class="fe fe-home"></i>
                    {{ _('Go to home page') }}
                </a>
            @show
        </div>
    </div>
</div>
@stop
