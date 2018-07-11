<div class="form-group bmd-form-group {{ $parentClass or null }}">
    <label
        for="{{ $id or $name }}"
        class="bmd-label-floating {{ $labelClass or null }}">
        {{ $slot }}
    </label>

    <input
        type="{{ $type or 'text' }}"
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="form-control {{ $class or null }} @if($errors->has($name)) is-invalid @endif"
        value="{{ $value or null }}"
        {{ $attributes or null }} {{-- NOTE: To pass attributes with value do not use quotes. i.e: autocomplete=off --}}
    >

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif

    {{-- HINT shows only when on focus. HELP shows always. Do not use both at the same time or they will overlap --}}

    @isset($hint)
        <span class="bmd-help">{{ $hint }}</span>
    @endisset

    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>


