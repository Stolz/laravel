@extends('layouts.base')

@section('body')

    <nav>
        <ul>
            @notroute('home')<li><a href="{{ route('home') }}">{{ _('Home') }}</a></li>@endnotroute

            {{-- Links for authenticated users --}}
            @auth
                <li>
                    <a href="{{ route('me') }}">{{ _('Me') }}</a>
                    @inject('notificationRepository', 'App\Repositories\Contracts\NotificationRepository')
                    @if($unreadCount = $notificationRepository->countUnread(Auth::user()))
                    (<a href="{{ route('me.notifications') }}">{{ $unreadCount }}</a>)
                    @endif
                </li>
                <li><a href="{{ route('logout') }}">{{ _('Log out') }}</a></li>

            {{-- Links for guests --}}
            @else
                @notroute('login')<li><a href="{{ route('login') }}">{{ _('Login') }}</a></li>@endnotroute
            @endauth
        </ul>
    </nav>

    <div id="content">
        <h1>{{ config('app.name') }} <small>@yield('page.title')</small></h1>

        @include('flash-messages')

        @yield('content')
    </div>

@stop
