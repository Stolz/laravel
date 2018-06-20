@extends('layouts.app')

@section('page.title', _('Send password reset link'))

@section('content')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">

        @if(session('status'))
            @alert(['type' => 'success'])
                {{ session('status') }}
            @endalert
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">{{ _('E-Mail') }}</label>
                <input id="email" type="email" name="email" class="form-control @if($errors->has('email'))is-invalid @endif" placeholder="{{ _('Enter e-mail address') }}" value="{{ old('email') }}" required autofocus>
                @if($errors->has('email'))<div class="invalid-feedback">{{ $errors->first('email') }}</div>@endif
            </div>

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Send reset link to my e-mail') }}</button>
        </form>

    </div>
</div>
@endsection
