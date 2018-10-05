@extends('layouts.app')

@section('page.title', $title = _('Create user'))

@section('main')
    <form method="post" action="{{ route('access.user.store') }}" role="form" autocomplete="off">
        @csrf
        @card(['footerClass' => 'd-flex justify-content-between'])
            @slot('header')
                <div class="card-title">
                    <i class="fe fe-user-plus small"></i>
                    {{ $title }}
                </div>
                <div class="card-options">
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
                </div>
            @endslot

            @include('modules.access.user.form', ['isUpdate' => false])

            @slot('footer')
                @can('list', 'App\Models\User')
                    <a href="{{ previous_index_url(route('access.user.index')) }}" class="btn btn-outline-secondary">
                        <i class="fe fe-x"></i>
                        {{ _('Cancel') }}
                    </a>
                @endcan

                <button type="submit" class="btn btn-success">
                    <i class="fe fe-user-plus"></i>
                    {{ _('Create user') }}
                </button>
            @endslot
        @endcard
    </form>
@stop
