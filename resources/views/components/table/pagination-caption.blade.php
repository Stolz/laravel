{{ sprintf($slot, $paginator->firstItem(), $paginator->lastItem(), $paginator->total()) }}.

@if(! empty($reset) and request()->has('search'))
    <a href="{{ $reset }}">{{ _('Reset search') }}</a>
    {{ _('or') }}
    <a href="#" class="show-side">{{ _('try with other values') }}</a>.
@endif
