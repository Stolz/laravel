<div class="form-group {{ $parentClass ?? null }}">
    <label
        for="{{ $id ?? $name }}"
        class="form-label {{ $labelClass ?? null }}">
        {{ $slot }}
        @if(isset($attributes) and str_contains($attributes, 'required'))
            <span class="form-required">*</span>
        @endif
    </label>

    @isset($icon)
        <div class="input-icon"><span class="input-icon-addon"><i class="{{ $icon }}"></i></span>
    @endisset

    <input
        type="{{ $type ?? 'text' }}"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        class="form-control {{ $class ?? null }} @if($errors->has($name)) is-invalid state-invalid @elseif($errors->count() and strlen($value ?? null)) is-valid state-valid @endif"
        value="{{ $value ?? null }}"
        {{ $attributes ?? null }} {{-- NOTE: To pass attributes with value do not use quotes. i.e: autocomplete=off min=0 max=1 --}}
    >

    @isset($icon)
        </div>
    @endisset

    @if($errors->has($name))
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif

    @isset($help)
        <div class="small text-muted">{{ $help }}</div>
    @endisset
</div>
