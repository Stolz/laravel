@extends('layouts.app')

@section('page.title', _('My information'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $user->getName() }}</dd>

            <dt>{{ _('E-mail') }}</dt>
            <dd>{{ $user->getEmail() }}</dd>

            <dt>{{ _('Role') }}</dt>
            <dd>{{ $user->getRole() }}</dd>
        </dl>
    </div>
</div>
@endsection
