@extends('layouts.app')

@section('page.title', _('My information'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $user['name'] }}</dd>

            <dt>{{ _('E-mail') }}</dt>
            <dd>{{ $user['email'] }}</dd>

            <dt>{{ _('Role') }}</dt>
            <dd>{{ $user['role'] }}</dd>
        </dl>
    </div>
</div>
@endsection
