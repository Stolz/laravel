<div class="custom-control custom-checkbox {{ $parentClass or null }}">
    <input
        type="checkbox"
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="custom-control-input {{ $class or null }} @if($errors->has($name)) is-invalid @endif"
        @if(! empty($checked)) checked @endif
        {{ $attributes or null }} {{-- NOTE: To pass attributes with value do not use quotes. i.e: value=1 --}}>
    <label
        for="{{ $id or $name }}"
        class="custom-control-label {{ $labelClass or null }}">
        {{ $slot }}
    </label>

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif

    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>
