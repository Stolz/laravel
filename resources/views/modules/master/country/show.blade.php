@extends('layouts.app')

@section('page.title', $title = _('Country details'))

@section('main')
    @card(['footerClass' => 'd-flex justify-content-between'])
        @slot('header')
            <div class="card-title">{{ $title }}</div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

        <div class="d-flex justify-content-between">
            <dl>
                <dt>{{ _('Name') }}</dt>
                <dd>{{ $country['name'] }}</dd>
            </dl>
            <dl>
                <dt>{{ _('Code') }}</dt>
                <dd>{{ $country['code'] }}</dd>
            </dl>
            <dl>
                <dt>{{ _('Flag') }}</dt>
                <dd>@flag(['country' => $country])@endflag</dd>
            </dl>
        </div>

        @slot('footer')
            @can('list', 'App\Models\Country')
                <a href="{{ previous_index_url(route('master.country.index')) }}" class="btn btn-outline-secondary">
                    <i class="fe fe-arrow-left"></i>
                    {{ _('Return') }}
                </a>
            @endcan

            @can('update', $country)
                <a href="{{ route('master.country.edit', [$country['id']]) }}" class="btn btn-primary">
                    <i class="fe fe-edit-2"></i>
                    {{ _('Edit') }}
                </a>
            @endcan

            @can('delete', $country)
                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $country['id'] }}">
                    <i class="fe fe-trash-2"></i>
                    {{ _('Delete') }}
                </a>
                @deleteModelModal(['model' => $country, 'action' => route('master.country.destroy', [$country['id']])]) @enddeleteModelModal
            @endcan
        @endslot
    @endcard
@stop
