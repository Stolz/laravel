{{ sprintf($slot, $paginator->firstItem(), $paginator->lastItem(), $paginator->total()) }}.

@if(! empty($reset) and request()->has('search'))
    <a href="{{ $reset }}">{{ _('Reset search') }}</a>
    {{ _('or') }}
    <a href="#" class="show-side">{{ _('try another one') }}</a>.
@endif
