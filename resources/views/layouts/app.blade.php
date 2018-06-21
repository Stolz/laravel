@extends('layouts.base')

@push('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush

@push('js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush

@section('body')

    @include('top')

    <main class="container-fluid">
        @include('flash-messages')
        @yield('content')
    </main>

@stop
