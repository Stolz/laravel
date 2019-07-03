<div class="row align-items-center">

    {{-- Right side of the navigation. It shows first on mobile --}}
    {{-- <div class="col-lg-3 ml-auto">
        <form class="input-icon my-3 my-lg-0">
            <input type="search" class="form-control header-search" placeholder="Search&hellip;">
            <div class="input-icon-addon">
                <i class="fe fe-search"></i>
            </div>
        </form>
    </div>--}}

    {{-- Left side of the navigation. It shows first on large screens --}}
    <div class="col-lg order-lg-first">
        <ul class="nav nav-tabs border-0 flex-column flex-lg-row">

            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link @route('home') active @endroute">
                    <i class="fe fe-home"></i>
                    {{ _('Home') }}
                </a>
            </li>

            {{-- Access module --}}
            @can('access', 'module')
                <li class="nav-item">
                    <a class="nav-link @route('access.*') active @endroute" data-toggle="dropdown">
                        <i class="fe fe-users"></i>
                        {{ _('Access') }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-arrow">
                        @can('list', 'App\Models\Role')
                            <a href="{{ route('access.role.index') }}" class="dropdown-item @route('access.role.*') active @endroute">
                                <i class="fe fe-shield"></i>
                                {{ _('Roles') }}
                            </a>
                        @endcan

                        @can('list', 'App\Models\User')
                            <a href="{{ route('access.user.index') }}" class="dropdown-item @route('access.user.*') active @endroute">
                                <i class="fe fe-user"></i>
                                {{ _('Users') }}
                            </a>
                        @endcan
                    </div>
                </li>
            @endcan

            {{-- Master module --}}
            @can('master', 'module')
                <li class="nav-item">
                    <a class="nav-link @route('master.*') active @endroute" data-toggle="dropdown">
                        <i class="fe fe-box"></i>
                        {{ _('Master') }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-arrow">
                        @can('list', 'App\Models\Country')
                            <a href="{{ route('master.country.index') }}" class="dropdown-item @route('master.country.*') active @endroute">
                                <i class="fe fe-flag"></i>
                                {{ _('Countries') }}
                            </a>
                        @endcan
                    </div>
                </li>
            @endcan

        </ul>
    </div>
</div>
