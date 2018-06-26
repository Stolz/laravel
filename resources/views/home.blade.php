@extends('layouts.app')

@section('page.title', _('Home page'))

@section('main')
<div class="jumbotron" style="margin: 0 -15px">

    <h1 class="display-4">Welcome to {{ config('app.name') }}</h1>
    <p class="lead">Version {{ app()::VERSION }}</p>

    <hr class="my-4">

    <p>Front end built with <strong>Material Design for Bootstrap 4<strong>.</p>
    <p class="lead">
        <a class="btn btn-primary btn-lg" href="https://fezvrasta.github.io/bootstrap-material-design/" role="button" target="_blank">Learn more</a>
    </p>

</div>
@endsection
