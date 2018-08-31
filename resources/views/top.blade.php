<nav class="navbar navbar-dark bg-primary navbar-expand-sm">{{-- .navbar-expand-sm collapses the bar for small devices --}}

    {{-- Button to show side navigation --}}
    @hasSection('side')
    <button type="button" class="btn" data-toggle="drawer" data-target="#aside" aria-controls="aside" aria-expanded="false" aria-label="{{ ('Toggle side navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>
    @endif

    {{-- Branding and page title --}}
    <a class="navbar-brand d-none d-sm-block" href="{{ route('home') }}" title="{{ _('Home') }}">{{ config('app.name') }}</a>
    <span class="navbar-text">@yield('page.title')</span>

    {{-- Button to show top navigation on small devices --}}
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#top-nav" aria-controls="top-nav" aria-expanded="false" aria-label="{{ ('Toggle top navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Navigation links --}}
    <div id="top-nav" class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">{{-- .ml-auto move links to the right --}}

            {{-- Allow other views to insert their own navigation links --}}
            @stack('top')

            @can('access', 'module')
            <li class="nav-item">
                <a class="nav-link @route(['access', 'access.*']) active @endroute" href="{{ route('access.home') }}">{{ _('Access') }}</a>
            </li>
            @endcan

            @can('master', 'module')
            <li class="nav-item">
                <a class="nav-link @route(['master', 'master.*']) active @endroute" href="{{ route('master.home') }}">{{ _('Master') }}</a>
            </li>
            @endcan

            {{-- Links for authenticated users --}}
            @auth
                <li class="nav-item dropdown @route(['me', 'me.*']) active @endroute">
                    <a class="nav-link dropdown-toggle" href="{{ route('me') }}" id="meDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user() }}
                        @inject('notificationRepository', 'App\Repositories\Contracts\NotificationRepository')
                        <?php $unreadNotifications = $notificationRepository->countUnread(Auth::user()); ?>
                        <span class="unread-notifications-counter badge badge-pill badge-info" style="display:{{ ($unreadNotifications) ? 'auto' : 'none' }}">{{ $unreadNotifications }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="meDropdown">
                        <a class="dropdown-item" href="{{ route('me') }}">{{ _('Me') }}</a>
                        <a class="dropdown-item" href="{{ route('me.notifications') }}">
                            {{ _('Notifications') }}
                            &nbsp;<span class="unread-notifications-counter badge badge-pill badge-primary" style="display:{{ ($unreadNotifications) ? 'auto' : 'none' }}">{{ $unreadNotifications }}</span>
                        </a>
                        <a class="dropdown-item" href="{{ route('me.password') }}">{{ _('Change password') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">{{ _('Log out') }}</a>
                    </div>
                </li>

            {{-- Links for guests --}}
            @else
                @notroute(['home', 'login'])
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ _('Login') }}</a>
                </li>
                @endnotroute
            @endauth
        </ul>
    </div>

</nav>

