<div class="form-group {{ $parentClass or null }}">
    <label
        for="{{ $id or $name }}"
        class="form-label {{ $labelClass or null }}">
        {{ $slot }}
        @if(isset($attributes) and str_contains($attributes, 'required'))
            <span class="form-required">*</span>
        @endif
    </label>

    @isset($icon)
        <div class="input-icon"><span class="input-icon-addon"><i class="{{ $icon }}"></i></span>
    @endisset

    <input
        type="{{ $type or 'text' }}"
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="form-control {{ $class or null }} @if($errors->has($name)) is-invalid state-invalid @elseif($errors->count()) is-valid state-valid @endif"
        value="{{ $value or null }}"
        {{ $attributes or null }} {{-- NOTE: To pass attributes with value do not use quotes. i.e: autocomplete=off min=0 max=1 --}}
    >

    @isset($icon)
        </div>
    @endisset

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif

    @isset($help)
        <div class="small text-muted">{{ $help }}</div>
    @endisset
</div>
