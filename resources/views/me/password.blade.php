@extends('layouts.app')

@section('page.title', _('Change password'))

@section('content')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <form method="POST" action="{{ route('me.password.change') }}">
            @csrf

            <div class="form-group">
                <label for="password">{{ _('Current password') }}</label>
                <input id="password" type="password" name="password" class="form-control @if($errors->has('password'))is-invalid @endif" placeholder="{{ _('Enter your current password') }}" required>
                @if($errors->has('password'))<div class="invalid-feedback">{{ $errors->first('password') }}</div>@endif
            </div>

            <div class="form-group">
                <label for="new_password">{{ _('New password') }}</label>
                <input id="new_password" type="password" name="new_password" class="form-control @if($errors->has('new_password'))is-invalid @endif" placeholder="{{ _('Enter desired new password again') }}" aria-describedby="passwordHelpBlock" required>
                @if($errors->has('new_password'))<div class="invalid-feedback">{{ $errors->first('new_password') }}</div>@endif
                <small id="passwordHelpBlock" class="form-text text-muted">
                    {{ sprintf(_('Your password must be at least %d characters long'), \App\Models\User::MIN_PASSWORD_LENGTH) }}
                </small>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">{{ _('Repeat new password') }}</label>
                <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control @if($errors->has('new_password_confirmation'))is-invalid @endif" placeholder="{{ _('Enter new password again') }}" required>
                @if($errors->has('new_password_confirmation'))<div class="invalid-feedback">{{ $errors->first('new_password_confirmation') }}</div>@endif
            </div>

            <button type="submit" class="btn btn-outline-primary btn-block mt-4" role="button" aria-pressed="true">{{ _('Change password') }}</button>

        </form>
    </div>
</div>
@endsection
