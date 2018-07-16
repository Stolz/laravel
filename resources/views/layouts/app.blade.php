@extends('layouts.base')

@prepend('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endprepend

@prepend('js')
    <script src="{{ mix('js/app.js') }}"></script>
@endprepend

@section('body')
<div id="container" class="bmd-layout-container bmd-drawer-f-l">

    <header id="header" class="bmd-layout-header">
        @include('top')
    </header>

    <aside id="aside" class="bmd-layout-drawer">
        @yield('side')
    </aside>

    <main id="main" class="bmd-layout-content container-fluid h-100">
        @include('flash-messages')
        @yield('main')
    </main>

</div>
@stop
