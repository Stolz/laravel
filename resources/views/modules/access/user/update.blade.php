@extends('layouts.app')

@section('page.title', $title = _('Update user'))

@section('main')
    <form method="post" action="{{ route('access.user.update', $user['id']) }}" role="form" autocomplete="off">
        @csrf @method('put')
        @card(['footerClass' => 'd-flex justify-content-between'])
            @slot('header')
                <div class="card-title">
                    <i class="fe fe-user small"></i>
                    {{ $title }}
                </div>
                <div class="card-options">
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
                </div>
            @endslot

            @include('modules.access.user.form', ['isUpdate' => true])

            @slot('footer')
                @can('list', 'App\Models\User')
                    <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary">
                        <i class="fe fe-x"></i>
                        {{ _('Cancel') }}
                    </a>
                @endcan

                <button type="submit" class="btn btn-primary">
                    <i class="fe fe-edit-3"></i>
                    {{ _('Update user') }}
                </button>
            @endslot
        @endcard
    </form>
@stop
