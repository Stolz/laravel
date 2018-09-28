<div class="d-flex">

    {{-- Branding --}}
    <a class="header-brand" href="{{ route('home') }}" title="{{ _('Home') }}">
        {{--<img src="images/logo.png" class="header-brand-img" alt="">--}}
        {{ config('app.name') }}
    </a>

    {{-- Right side of the header --}}
    <div class="d-flex order-lg-2 ml-auto">

        @auth
            <?php
                // NOTE: JavaScript will remove the "display: none" style of all .unread-notifications elements whenever there are unread notifications
                $notificationRepository = app('App\Repositories\Contracts\NotificationRepository');
                $unreadNotifications = $notificationRepository->countUnread($authUser = Auth::user());
                $display = ($unreadNotifications) ? 'display:auto' : 'display:none';
            ?>

            {{-- Notification bell and preview dropdown --}}
            <div class="dropdown d-none d-md-flex">

                {{-- Notification bell --}}
                <a class="nav-link icon" data-toggle="dropdown">
                    <i class="fe fe-bell"></i>
                    <span class="unread-notifications nav-unread" style="{{ $display }}"></span>
                </a>

                {{-- Notification preview dropdown --}}
                <div id="notifications" class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    {{-- Original notification format, with avatar and date
                        <a href="#" class="dropdown-item d-flex">
                        <span class="avatar mr-3 align-self-center" style="background-image: url('foo')"></span>
                        <div>
                            <strong>Who</strong> What.
                            <div class="small text-muted">When</div>
                        </div>
                    </a>--}}

                    <div class="unread-notifications dropdown-divider" style="{{ $display }}"></div>

                    <a href="{{ route('me.notifications') }}" class="dropdown-item text-center text-muted-dark">
                        {{ _('See all notifications') }}
                    </a>
                </div>
            </div>

            {{-- Current user dropdown --}}
            <div class="dropdown">

                {{-- Name, role and avatar --}}
                <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                    @avatar(['user' => $authUser])@endavatar
                    <span class="ml-2 d-none d-lg-block">
                        <span class="text-default">{{ $authUser }}</span>
                        <small class="text-muted d-block mt-1">{{ $authUser['role'] }}</small>
                    </span>
                </a>

                {{-- Dropdown --}}
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="{{ route('me') }}">
                        <i class="dropdown-icon fe fe-user"></i>
                        {{ _('Profile') }}
                    </a>

                    <a class="dropdown-item" href="{{ route('me.password') }}">
                        <i class="dropdown-icon fe fe-settings"></i>
                        {{ _('Change password') }}
                    </a>

                    <a class="dropdown-item" href="{{ route('me.notifications') }}">
                        <span class="unread-notifications unread-notifications-counter float-right badge badge-primary" style="{{ $display }}">
                            {{ $unreadNotifications }}
                        </span>
                        <i class="dropdown-icon fe fe-mail"></i>
                        {{ _('Notifications') }}
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="dropdown-icon fe fe-log-out"></i>
                        {{ _('Log out') }}
                    </a>
                </div>
            </div>
        @else
            @notroute(['home', 'login'])
                <div class="nav-item d-none d-md-flex">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('login') }}">{{ _('Login') }}</a>
                </div>
            @endnotroute
        @endauth
    </div>

    {{-- Button to toggle top navigation on small devices --}}
    <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
        <span class="header-toggler-icon"></span>
    </a>
</div>

{{-- Show preview of last unread notification --}}
@if ($unreadNotifications ?? false)
    @push('js')
        <script>
        $(function () {
            notifications.show(@json($notificationRepository->getLastUnread($authUser)->jsonSerialize()));
        });
        </script>
    @endpush
@endif
