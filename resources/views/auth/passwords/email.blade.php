@extends('layouts.app')

@section('page.title', _('Send password reset link'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">

        @if(session('status'))
            @alert(['type' => 'success'])
                {{ session('status') }}
            @endalert
        @endif

        <form method="post" action="{{ route('password.email') }}" role="form" autocomplete="off">
            @csrf

            @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'required autofocus maxlength=255'])
                {{ _('E-Mail') }}
            @endinput

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Send reset link to my e-mail') }}</button>
        </form>

    </div>
</div>
@endsection
