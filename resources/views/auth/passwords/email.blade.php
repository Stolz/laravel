@extends('layouts.app')

@section('page.title', _('Send password reset link'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        @if(session('status'))
            @alert(['type' => 'success'])
                {{ session('status') }}
            @endalert
        @endif

        <form method="post" action="{{ route('password.email') }}">
            @csrf

            @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'required autofocus'])
                {{ _('E-Mail') }}
            @endinput

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Send reset link to my e-mail') }}</button>
        </form>

    </div>
</div>
@endsection
