@extends('layouts.base')

@section('page-title', _('Welcome'))
@section('page-description', _('Welcome page'))

@section('body')
<h1>Welcome to {{ config('app.name') }}</h1>
@endsection
