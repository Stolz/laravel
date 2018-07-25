@extends('layouts.app')

@section('page.title', _('Update country'))

@section('main')
<div class="row justify-content-center">
    <div class="col-sm-9 col-md-7 col-lg-5 col-xl-4">

        <form method="post" action="{{ route('master.country.update', $country['id']) }}" role="form" autocomplete="off">
            @csrf @method('put')
            @include('modules.master.country.form')

            <div class="row">
                @can('list', 'App\Models\Country')
                    <div class="col">
                        <a href="{{ previous_index_url(route('master.country.index')) }}" class="btn btn-outline-secondary btn-block">{{ _('Cancel') }}</a>
                    </div>
                @endcan
                <div class="col">
                    <button type="submit" class="btn btn-primary active btn-block">{{ _('Update country') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>
@stop
