@extends('layouts.app')

@section('page.title', $title = _('Request password reset'))

@section('main')
<div class="row mt-md-7">
    <div class="col col-login mx-auto">

        @if(session('status'))
            @alert(['type' => 'info'])
                {{ session('status') }}
            @endalert
        @endif

        @card
            <form method="post" action="{{ route('password.email') }}" role="form" autocomplete="off">
                @csrf
                <div class="card-title">{{ $title }}</div>
                <p class="text-muted">{{ _('Please enter your e-mail address and we will send you a message with instructions to reset your password.') }}</p>

                @input(['type' => 'email', 'name' => 'email', 'value' => old('email'), 'attributes' => 'required autofocus maxlength=255'])
                    {{ _('E-Mail') }}
                @endinput

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-block" role="button">
                        <i class="fe fe-mail mr-1"></i>
                        {{ _('Send e-mail') }}
                    </button>
                </div>
            </form>
        @endcard

    </div>
</div>
@endsection
