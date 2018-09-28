@extends('layouts.base')

@prepend('css')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endprepend

@prepend('js')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    {{-- Notifications via server-sent events --}}
    @auth <script>var serverSentEventsUrl = '{{ route('me.notifications.stream') }}';</script> @endauth
@endprepend

@section('body')
<div class="page">
    <div class="page-main">

        {{-- Header --}}
        <div class="header">
            <div class="container">
                @include('header')
            </div><!--.container-->
        </div><!--.header-->

        {{-- Top navigation --}}
        @auth
            <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
                <div class="container">
                    @include('top')
                </div><!--.container-->
            </div><!--.header-->
        @endauth

        {{-- Main content --}}
        <div class="@yield('container', 'container') my-3 my-md-5">{{-- i.e: container-liquid --}}
            <div class="row">
                @hasSection('side')
                    <div id="side" class="col-lg-3 order-lg-last" style="display: none">@yield('side')</div>
                @endif
                <div class="col-lg">
                    @include('flash-messages')
                    @yield('main')
                </div>
            </div>
        </div><!--.container-->
    </div><!--.page-main-->

    {{-- Bottom navigation
    <div class="footer">
        <div class="container">
            @ include('bottom')
        </div><!--.container-->
    </div><!--.footer-->--}}

    {{-- Footer (Copyright and legal) --}}
    <footer class="footer">
        <div class="container">
            @include('footer')
        </div><!--.container-->
    </footer>
</div><!--.page-->
@stop
