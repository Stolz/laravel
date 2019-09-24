@extends('layouts.app')

@section('page.title', $title = _('Create announcement'))

@section('main')
    <form method="post" action="{{ route('master.announcement.store') }}" role="form" autocomplete="off">
        @csrf
        @card(['footerClass' => 'd-flex justify-content-between'])
            @slot('header')
                <div class="card-title">{{ $title }}</div>
                <div class="card-options">
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
                </div>
            @endslot

            @include('modules.master.announcement.form')

            @slot('footer')
                @can('list', 'App\Models\Announcement')
                    <a href="{{ previous_index_url(route('master.announcement.index')) }}" class="btn btn-outline-secondary">
                        <i class="fe fe-x"></i>
                        {{ _('Cancel') }}
                    </a>
                @endcan

                <button type="submit" class="btn btn-success">
                    <i class="fe fe-plus"></i>
                    {{ _('Create announcement') }}
                </button>
            @endslot
        @endcard
    </form>
@stop
