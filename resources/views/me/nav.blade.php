<nav>
    <ul>
        @notroute('me.password')<li><a href="{{ route('me.password') }}">{{ _('Change password') }}</a></li>@endnotroute
        @notroute('me.notifications')<li><a href="{{ route('me.notifications') }}">{{ _('Notifications') }}</a></li>@endnotroute
    </ul>
</nav>
