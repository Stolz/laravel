@extends('layouts.app')

@section('page.title', _('My information'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">
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
