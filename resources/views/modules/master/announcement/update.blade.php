@extends('layouts.app')

@section('page.title', $title = _('Update announcement'))

@section('main')
    <form method="post" action="{{ route('master.announcement.update', $announcement['id']) }}" role="form" autocomplete="off">
        @csrf @method('put')
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

                <button type="submit" class="btn btn-primary">
                    <i class="fe fe-edit-3"></i>
                    {{ _('Update announcement') }}
                </button>
            @endslot
        @endcard
    </form>
    <!--TEST BEACON update-->{{-- Do not remove. Used for automatic testing --}}
@stop
