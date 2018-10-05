<?php $required = (isset($attributes) and str_contains($attributes, 'required')); ?>
<div class="custom-control custom-checkbox {{ $parentClass ?? null }}">
    <input
        type="checkbox"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        class="custom-control-input {{ $class ?? null }} @if($errors->has($name)) is-invalid state-invalid @elseif($required and $errors->count()) is-valid state-valid @endif"
        @if(! empty($checked)) checked @endif
        {{ $attributes ?? null }} {{-- NOTE: To pass attributes with value do not use quotes. i.e: value=1 --}}>
    <label
        for="{{ $id ?? $name }}"
        class="custom-control-label {{ $labelClass ?? null }}">
        {{ $slot }}
        @if($required)
            <span class="form-required">*</span>
        @endif
    </label>

    @if($errors->has($name))
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif

    @isset($help)
        <div class="small text-muted">{{ $help }}</div>
    @endisset
</div>
