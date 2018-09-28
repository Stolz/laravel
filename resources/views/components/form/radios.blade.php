<?php
    if ($errors->has($name))
        $valid = 'is-invalid state-invalid';
    elseif ($errors->count())
        $valid = 'is-valid state-valid';
else $valid = '';
?>

<div class="form-group {{ $parentClass or null }}">
    <label
        for="{{ $id or $name }}"
        class="form-label {{ $labelClass or null }} {{$valid}}">
        {{ $slot }}
        @if(isset($attributes) and str_contains($attributes, 'required'))
            <span class="form-required">*</span>
        @endif
    </label>

    @foreach($options as $value => $option)
        <?php
        $value = ($option instanceof \App\Models\Model) ? $option['id'] : $value;
        $selectCurrent = (is_scalar($selected)) ? $selected == $value : $selected['id'] == $value;
        ?>
        <div class="custom-control custom-radio">
            <input
                type="radio"
                id="{{ $id or $name }}{{ $value }}"
                name="{{ $name }}"
                class="custom-control-input {{ $class or null }} {{$valid}}"
                value="{{ $value }}"
                @if($selectCurrent) checked @endif>
            <label
                for="{{ $id or $name }}{{ $value }}"
                class="custom-control-label">
                {{ $option }}
            </label>
        </div>
    @endforeach

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div> {{-- TODO not been shown. Some JS replaces it with a comment --}}
    @endif

    @isset($help)
        <div class="small text-muted">{{ $help }}</div>
    @endisset
</div>
