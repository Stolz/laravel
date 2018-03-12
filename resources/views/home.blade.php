@extends('layouts.app')

@section('page-title', _('Home'))
@section('page-description', _('Home page'))

@section('content')
<p>Welcome to Laravel {{ app()::VERSION }}!!</p>
@endsection
