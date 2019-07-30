@extends('layouts.app')

@section('page.title', $title = _('Notifications'))

@section('main')

    <h1 class="page-title">{{ $title }}</h1>

    @if(! $notifications->count())
        @alert(['type' => 'info'])
            {{ _('There are no notifications') }}
        @endalert
    @else
        <form method="post" action="{{ route('me.notification.read') }}" role="form" autocomplete="off">
            @csrf
            <?php $icons = ['info' => 'fe fe-info', 'success' => 'fe fe-check-circle', 'warning' => 'fe fe-alert-triangle', 'error' => 'fe fe-alert-octagon']; ?>
            @foreach($notifications as $notification)
                @alert(['type' => $type = ($notification->isUnread()) ? $notification->getLevel() : 'secondary', 'icon' => $icons[$notification->getLevel()]])

                    {{-- Notifification body --}}
                    @slot('title')
                        {{ $notification->getMessage() }}
                        @if($url = $notification->getActionUrl())
                            <a class="alert-link" href="{{ $url }}">{{ $notification->getActionText() }}</a>
                        @endif
                    @endslot

                    <hr class="mt-0 mb-1">

                    {{-- Notifification relative date --}}
                    <span title="{{ date_in_user_timezone($notification->getCreatedAt()) }}">
                        {{ $notification->getCreatedAt()->diffForHumans() }}
                    </span>

                    {{-- Button to mark notifification as read --}}
                    @if($notification->isUnread())
                    <button name="notification" class="btn btn-{{ ($type === 'error' ? 'danger' : $type) }} btn-sm float-right" value="{{ $notification->getId() }}">
                        <i class="fe fe-check"></i>
                        {{ _('Mark as read') }}
                    </button>
                    @endif

                @endalert
            @endforeach

            {{ $notifications->links('pagination') }}
        </form>
    @endif

@endsection
