{{ sprintf($slot, $paginator->firstItem(), $paginator->lastItem(), $paginator->total()) }}.

@if(! empty($reset) and request()->has('search'))
    <a href="{{ $reset }}">{{ _('Reset search options') }}</a>
    {{ _('or') }}
    <a href="#" onclick="return false" data-toggle="drawer" data-target="#aside">{{ _('try with different ones') }}</a>.
@endif
