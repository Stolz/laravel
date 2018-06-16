<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>@yield('page.title') | {{ config('app.name') }}</title>

    @hasSection('page.description')
    <meta name="description" content="@yield('page.description')">
    @endif

    <base href="/" />
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1" />{{-- Force latest IE rendering engine or Chrome Frame if available --}}
    <meta name="author" content="twitter: @Stolz" />
    <meta name="csrf-token" content="{{ csrf_token() }}">{{-- Used for AJAX CSRF --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">{{-- Webkit. To disable zooming add ", maximum-scale=1, user-scalable=no" --}}
    <link rel="icon" href="favicon.ico" type="image/x-icon" />{{-- Favicon  --}}

    {{-- CSS  --}}
    @stack('css')
</head>
<body>

    <!--[if lte IE 9]><h1 style="position:absolute;top:0;z-index:10000; color:black; background-color:orange">
        You are using an outdated browser.
        Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.
    </h1><![endif]-->

    @yield('body')

    {{-- JavaScript  --}}
    @stack('js')
</body>
</html>
