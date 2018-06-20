@extends('layouts.app')

@section('page.title', _('Notifications'))

@section('content')

    @if(! $notifications->count())
        {{ _('There are no notifications') }}
    @else
        <form method="POST" action="{{ route('me.notification.read') }}">
            @csrf
            @foreach($notifications as $notification)
                <?php $type = ($notification->isUnread()) ? $notification->getLevel() : 'secondary' ?>
                @alert(['type' => $type])
                    <div class="row no-gutters align-items-center">

                        {{-- Notifification body --}}
                        <div class="col">
                            <b>{{ $notification->getMessage() }}</b>
                            @if($url = $notification->getActionUrl())
                                <a class="alert-link" href="{{ $url }}">{{ $notification->getActionText() }}</a>
                            @endif
                        </div>

                        {{-- Button to mark notifification as read --}}
                        @if($notification->isUnread())
                        <div class="col-md-2 col-lg-1">
                            {{-- Button to mark notification as read --}}
                            <button name="notification" class="btn btn-outline-{{ ($type === 'error' ? 'danger' : $type) }}" value="{{ $notification->getId() }}">{{ _('Mark as read') }}</button>
                        </div>
                        @endif

                        {{-- Notifification relative date --}}
                        <div class="col-md-2 col-lg-1 text-right">
                            {{ $notification->getCreatedAt()->diffForHumans() }}
                        </div>
                    </div>

                @endalert
            @endforeach

            {{ $notifications->links('pagination') }}
        </form>
    @endif

@endsection
