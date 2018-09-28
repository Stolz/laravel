<div class="text-center">
    <ul class="list-inline list-inline-dots mb-0">
        <li class="list-inline-item">Copyright &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong></li>
        <li class="list-inline-item">{{ _('All rights reserved') }}</li>

        {{-- Non production environment reminder --}}
        @if(! app()->environment('production'))
        <li class="list-inline-item">
            <span class="badge badge-secondary m-0">{{ sprintf(_('%s environment'), app()->environment()) }}</span>
        </li>
        @endif
    </ul>
</div>
