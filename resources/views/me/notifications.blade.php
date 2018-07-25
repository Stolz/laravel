@extends('layouts.app')

@section('page.title', _('Notifications'))

@section('main')

    @if(! $notifications->count())
        {{ _('There are no notifications') }}
    @else
        <form method="post" action="{{ route('me.notification.read') }}" autocomplete="off">
            @csrf
            @foreach($notifications as $notification)
                <?php $type = ($notification->isUnread()) ? $notification->getLevel() : 'secondary' ?>
                @alert(['type' => $type])
                    <div class="row no-gutters justify-content-between align-items-center">

                        {{-- Notifification body --}}
                        <div class="col">
                            <b>{{ $notification->getMessage() }}</b>
                            @if($url = $notification->getActionUrl())
                                <a class="alert-link" href="{{ $url }}">{{ $notification->getActionText() }}</a>
                            @endif
                        </div>

                        {{-- Button to mark notifification as read --}}
                        @if($notification->isUnread())
                        <div class="col-3 col-md-2 col-xl-1 text-center"">
                            {{-- Button to mark notification as read --}}
                            <button name="notification" class="btn btn-outline-{{ ($type === 'error' ? 'danger' : $type) }} btn-sm" value="{{ $notification->getId() }}">{{ _('Mark as read') }}</button>
                        </div>
                        @endif

                        {{-- Notifification relative date --}}
                        <div class="col-sm-3 col-md-2 col-xl-1 text-right" title="{{ $createdAt = $notification->getCreatedAt() }}">
                            {{ $createdAt->diffForHumans() }}
                        </div>
                    </div>

                @endalert
            @endforeach

            {{ $notifications->links('pagination') }}
        </form>
    @endif

@endsection
