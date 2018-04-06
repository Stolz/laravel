@extends('layouts.app')

@section('page.title', _('Notifications'))

@section('content')

    @include('me.nav')

    @if(! $notifications->count())
        {{ _('There are no notifications') }}
    @else
        <form method="POST" action="{{ route('me.notification.read') }}">
            @csrf
            <table summary="">
                <tbody>
                @foreach($notifications as $notification)
                    <tr class="{{ $notification->getLevel() }}">
                        <td>{{ $notification->getCreatedAt()->diffForHumans() }}</td>
                        <td>
                            <b>{{ $notification->getMessage() }}</b>

                            @if($url = $notification->getActionUrl())
                                <a href="{{ $url }}">{{ $notification->getActionText() }}</a>
                            @endif
                        </td>

                        {{-- Button to mark notification as read --}}
                        @if($notification->isUnread())
                            <td>
                                <button name="notification" value="{{ $notification->getId() }}">{{ _('Mark as read') }}</button>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>

            </table>
            {{ $notifications->links('pagination') }}
        </form>
    @endif

@endsection
