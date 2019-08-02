@extends('layouts.app')

@section('page.title', $title = _('Announcements'))

@section('main')

    <!--TEST BEACON index-->{{-- Do not remove. Used for automatic testing --}}
    @card
        @slot('header')
            <div class="card-title">{{ $title }}</div>
            <div class="card-options">
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
            </div>
        @endslot

        @if($announcements->isEmpty())
            @noResultsAlert()
                {{ _('No announcements found') }}
            @endnoResultsAlert
        @else
            @table
                @slot('caption')
                    @tableCaption(['paginator' => $announcements])
                        {{ _('Showing announcements %d to %d out of %d') }}
                    @endtableCaption
                @endslot

                @slot('header')
                    <tr>
                        <th>{{ _('Actions') }}</th>
                        @tableHeaders(['headers' => [
                            'active' => _('Active'),
                            'name' => _('Name'),
                            'createdAt' => _('Created'),
                            'updatedAt' => _('Updated'),
                        ]]) @endtableHeaders
                    </tr>
                @endslot

                @foreach($announcements as $announcement)
                    <tr>
                        <td class="actions">@include('modules.master.announcement.actions')</td>
                        <td>{{ $announcement['active'] ? _('Yes') : _('No') }}</td>
                        <td>{{ $announcement['name'] }}</td>
                        <td title="{{ date_in_user_timezone($announcement['createdAt']) }}">
                            {{ $announcement['createdAt'] ? $announcement['createdAt']->diffForHumans() : null }}
                        </td>
                        <td title="{{ date_in_user_timezone($announcement['updatedAt']) }}">
                            {{ $announcement['updatedAt'] ? $announcement['updatedAt']->diffForHumans() : null }}
                        </td>
                    </tr>
                @endforeach

            @endtable

            {{ $announcements->links('pagination') }}

            @foreach ($announcements as $announcement)
                @deleteModelModal([
                    'model' => $announcement,
                    'action' => route('master.announcement.destroy', [$announcement['id']]),
                ])
                @enddeleteModelModal
            @endforeach
        @endif

        @can('create', 'App\Models\Announcement')
            @slot('footer')
                <a href="{{ route('master.announcement.create') }}" class="btn btn-success">
                    <i class="fe fe-plus"></i>
                    {{ _('Create new announcement') }}
                </a>
            @endslot
        @endcan

    @endcard

@stop
