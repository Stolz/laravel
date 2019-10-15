@extends('layouts.app')

@section('page.title', $title = _('Countries'))

@section('main')
    @card
        @slot('header')
            <div class="card-title">
                <i class="fe fe-flag small"></i>
                {{ $title }}
            </div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

        @if($countries->isEmpty())
            @noResultsAlert
                {{ _('No countries found') }}
            @endnoResultsAlert
        @else
            @table
                @slot('caption')
                    @tableCaption(['paginator' => $countries])
                        {{ _('Showing countries %d to %d out of %d') }}
                    @endtableCaption
                @endslot

                @slot('header')
                    <tr>
                        <th>{{ _('Actions') }}</th>
                        @tableHeaders(['headers' => [
                            'name' => _('Name'),
                            'code' => _('Code'),
                            '_flag' => _('Flag'),
                            'createdAt' => _('Created'),
                            'updatedAt' => _('Updated'),
                        ]]) @endtableHeaders
                    </tr>
                @endslot

                @foreach($countries as $country)
                    <tr>
                        <td class="actions">@include('modules.master.country.actions')</td>
                        <td>{{ $country['name'] }}</td>
                        <td>{{ $country['code'] }}</td>
                        <td>@flag(['country' => $country])@endflag</td>
                        <td title="{{ date_in_user_timezone($country['createdAt']) }}">
                            {{ $country['createdAt'] ? $country['createdAt']->diffForHumans() : null }}
                        </td>
                        <td title="{{ date_in_user_timezone($country['updatedAt']) }}">
                            {{ $country['updatedAt'] ? $country['updatedAt']->diffForHumans() : null }}
                        </td>
                    </tr>
                @endforeach

            @endtable

            {{ $countries->links('pagination') }}

            @foreach ($countries as $country)
                @deleteModelModal([
                    'model' => $country,
                    'action' => route('master.country.destroy', [$country['id']]),
                ])
                @enddeleteModelModal
            @endforeach
        @endif

        @can('create', 'App\Models\Country')
            @slot('footer')
                <a href="{{ route('master.country.create') }}" class="btn btn-success">
                    <i class="fe fe-plus"></i>
                    {{ _('Create new country') }}
                </a>
            @endslot
        @endcan

    @endcard
    <!--TEST BEACON index-->{{-- Do not remove. Used for automatic testing --}}
@stop
