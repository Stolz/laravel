@extends('layouts.app')

@section('page-title', _('User'))
@section('page-description', _('User home page'))

@section('content')
<p>You are logged in!</p>
<pre>@json(Auth::user()->toArrayExcept(['password', 'remember_token']), JSON_PRETTY_PRINT)</pre>
@endsection
