@extends('layouts.app')

@section('page.title', _('Countries'))

@section('main')

    @forelse ($countries as $country)

        {{-- Table header --}}
        @if ($loop->first)
            <div class="table-responsive">
                <table class="table table-responsive table-striped table-hover">
                    <caption>{{ _('List of countries') }}</caption>
                    <thead>
                        <tr>
                            <th>{{ _('Actions') }}</th>
                            <th>{{ _('Name') }}</th>
                        </tr>
                    </thead>
                <tbody>
        @endif

        {{-- Table body --}}
        <tr>
            <td>
                <div class="btn-group-sm" role="group" aria-label="{{ _('Actions') }}">
                    @can('view', $country)
                        <a href="{{ route('master.country.show', [$country['id']]) }}" class="btn btn-info">{{ _('View') }}</a>
                    @else
                        <a href="#" class="btn btn-info disabled">{{ _('View') }}</a>
                    @endcan
                    @can('edit', $country)
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
        </tr>

        {{-- Table footer --}}
        @if ($loop->last)
                    </tbody>
                </table>
            </div><!--.table-responsive-->

            {{ $countries->links('pagination') }}
        @endif

    @empty
        @alert(['type' => 'info'])
            {{ _('No countries found') }}
        @endalert
    @endforelse

    {{-- Delete modals --}}
    @foreach ($countries as $country)
        @deleteModelModal([
            'model' => $country,
            'action' => route('master.country.destroy', [$country['id']]),
        ])
        @enddeleteModelModal
    @endforeach

    @can('create', 'App\Models\Country')
        <a href="{{ route('master.country.create') }}" class="btn btn-success" >{{ _('Create new country') }}</a>
    @endcan

@stop
