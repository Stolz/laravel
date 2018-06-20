@extends('layouts.app')

@section('page.title', _('Home page'))

@section('content')

    <h1>Welcome to {{ config('app.name') }} {{ app()::VERSION }}</h1>

@endsection
