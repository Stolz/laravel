@extends('layouts.app')

@section('page.title', _('Countries'))

@section('main')
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
                ]]) @endtableHeaders
            </tr>
            @endslot

            @foreach($countries as $country)
            <tr>
                <td class="actions">
                    <div class="btn-group-sm" role="group" aria-label="{{ _('Actions') }}">
                        @can('view', $country)
                            <a href="{{ route('master.country.show', [$country['id']]) }}" class="btn btn-info">{{ _('View') }}</a>
                        @else
                            <a href="#" class="btn btn-info disabled">{{ _('View') }}</a>
                        @endcan
                        @can('update', $country)
                            <a href="{{ route('master.country.edit', [$country['id']]) }}" class="btn btn-primary">{{ _('Edit') }}</a>
                        @else
                            <a href="#" class="btn btn-primary disabled">{{ _('Edit') }}</a>
                        @endcan
                        @can('delete', $country)
                            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $country['id'] }}">{{ _('Delete') }}</a>
                        @else
                            <a href="#" class="btn btn-danger disabled">{{ _('Delete') }}</a>
                        @endcan
                    </div>
                </td>
                <td>{{ $country['name'] }}</td>
                <td>{{ $country['code'] }}</td>
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

    <a class="btn btn-secondary btn-raised" href="{{ route('master.home') }}">
        <i class="material-icons">view_module</i>
        {{ _('Back to module') }}
    </a>

    @can('create', 'App\Models\Country')
        <a href="{{ route('master.country.create') }}" class="btn btn-success btn-raised">
            <i class="material-icons">add</i>
            {{ _('Create new country') }}
        </a>
    @endcan
@stop
