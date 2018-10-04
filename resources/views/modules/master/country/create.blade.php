@extends('layouts.app')

@section('page.title', $title = _('Create country'))

@section('main')
    <form method="post" action="{{ route('master.country.store') }}" role="form" autocomplete="off">
        @csrf
        @card(['footerClass' => 'd-flex justify-content-between'])
            @slot('header')
                <div class="card-title">{{ $title }}</div>
                <div class="card-options">
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
                </div>
            @endslot

            @include('modules.master.country.form')

            @slot('footer')
                @can('list', 'App\Models\Country')
                    <a href="{{ previous_index_url(route('master.country.index')) }}" class="btn btn-outline-secondary">
                        <i class="fe fe-x"></i>
                        {{ _('Cancel') }}
                    </a>
                @endcan

                <button type="submit" class="btn btn-success">
                    <i class="fe fe-plus"></i>
                    {{ _('Create country') }}
                </button>
            @endslot
        @endcard
    </form>
@stop
