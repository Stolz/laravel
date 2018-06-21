<nav class="navbar navbar-dark bg-primary navbar-expand-sm">{{-- .navbar-expand-sm collapses the bar for small devices --}}

    {{-- Branding and page title --}}
    <a class="navbar-brand d-none d-sm-block" href="{{ route('home') }}" title="{{ _('Home') }}">{{ config('app.name') }}</a>
    <span class="navbar-text">@yield('page.title')</span>

    {{-- Button to show navigation on small devices --}}
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#topBarNavigation" aria-controls="topBarNavigation" aria-expanded="false" aria-label="{{ ('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Navigation links --}}
    <div id="topBarNavigation" class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">{{-- .ml-auto move links to the right --}}

            <li class="nav-item @route('home') active @endroute">
                <a class="nav-link" href="{{ route('home') }}">{{ _('Home') }}</a>
            </li>

            {{-- Links for authenticated users --}}
            @auth
                <li class="nav-item dropdown @route(['me', 'me.notifications', 'me.password']) active @endroute">
                    <a class="nav-link dropdown-toggle" href="{{ route('me') }}" id="meDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user() }}
                        @inject('notificationRepository', 'App\Repositories\Contracts\NotificationRepository')
                        @if($unreadNotifications = $notificationRepository->countUnread(Auth::user()))
                             <span class="badge badge-pill badge-info">{{ $unreadNotifications }}</span>
                        </a>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="meDropdown">
                        <a class="dropdown-item" href="{{ route('me') }}">{{ _('Me') }}</a>
                        <a class="dropdown-item" href="{{ route('me.notifications') }}">
                            {{ _('Notifications') }}
                            @if($unreadNotifications)
                                &nbsp;<span class="badge badge-pill badge-primary">{{ $unreadNotifications }}</span>
                            @endif
                        </a>
                        <a class="dropdown-item" href="{{ route('me.password') }}">{{ _('Change password') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">{{ _('Log out') }}</a>
                    </div>
                </li>

            {{-- Links for guests --}}
            @else
                @notroute('login')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ _('Login') }}</a>
                </li>
                @endnotroute
            @endauth
        </ul>
    </div>

</nav>
