@alert(['type' => 'info'])

    {{ $slot }}.

    @if(! empty($reset) and request()->has('search'))
        <a class="alert-link" href="{{ $reset }}">{{ _('Reset search') }}</a>
        {{ _('or') }}
        <a href="#" class="show-side alert-link">{{ _('try with other values') }}</a>.
    @endif

@endalert
