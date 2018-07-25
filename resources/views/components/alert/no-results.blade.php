@alert(['type' => 'info'])

    {{ $slot }}.

    @if(! empty($reset) and request()->has('search'))
        <a class="alert-link" href="{{ $reset }}">{{ _('Reset search options') }}</a>
        {{ _('or') }}
        <a href="#" onclick="return false" class="alert-link" data-toggle="drawer" data-target="#aside">{{ _('try with different ones') }}</a>.
    @endif

@endalert
