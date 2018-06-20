<div class="custom-control custom-checkbox {{ $parentClass or null }}">
    <input
        type="checkbox"
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="custom-control-input {{ $class or null }} @if($errors->has($name)) is-invalid @endif"
        @isset($checked) checked @endisset>
    <label
        for="{{ $id or $name }}"
        class="custom-control-label {{ $labelClass or null }}">
        {{ $slot }}
    </label>

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>
