@extends('layouts.app')

@section('page.title', _('Country'))

@section('main')
<div class="row justify-content-md-center">
    <div class="col col-md-10 col-lg-4">
        <dl>
            <dt>{{ _('Name') }}</dt>
            <dd>{{ $country['name'] }}</dd>
        </dl>

        <div class="row">
            @can('list', 'App\Models\Country')
                <div class="col">
                    <a href="{{ previous_index_url(route('master.country.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Return') }}</a>
                </div>
            @endcan
            @can('update', $country)
                <div class="col">
                    <a href="{{ route('master.country.edit', [$country['id']]) }}" class="btn btn btn-primary active btn-block">{{ _('Edit') }}</a>
                </div>
            @endcan
            @can('delete', $country)
                <div class="col">
                    <a href="#" class="btn btn btn-danger active btn-block" data-toggle="modal" data-target="#delete-modal-{{ $country['id'] }}">{{ _('Delete') }}</a>
                </div>
                @deleteModelModal(['model' => $country, 'action' => route('master.country.destroy', [$country['id']])])
                @enddeleteModelModal
            @endcan
        </div>
    </div>
</div>
@stop
