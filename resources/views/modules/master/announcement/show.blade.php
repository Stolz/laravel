@extends('layouts.app')

@section('page.title', $title = _('Announcement preview'))

@section('main')
    @card(['footerClass' => 'd-flex justify-content-between'])
        @slot('header')
            <div class="card-title">{{ $title }}</div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

        @include('modules.master.announcement.alert')

        @slot('footer')
            @can('list', 'App\Models\Announcement')
                <a href="{{ previous_index_url(route('master.announcement.index')) }}" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left"></i>
                    {{ _('Return') }}
                </a>
            @endcan

            @can('update', $announcement)
                <a href="{{ route('master.announcement.edit', [$announcement['id']]) }}" class="btn btn-primary">
                    <i class="fe fe-edit-2"></i>
                    {{ _('Edit') }}
                </a>
            @endcan

            @can('delete', $announcement)
                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $announcement['id'] }}">
                    <i class="fe fe-trash-2"></i>
                    {{ _('Delete') }}
                </a>
                @deleteModelModal(['model' => $announcement, 'action' => route('master.announcement.destroy', [$announcement['id']])])@enddeleteModelModal
            @endcan
        @endslot
    @endcard
@stop
