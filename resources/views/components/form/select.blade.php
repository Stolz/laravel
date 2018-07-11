<div class="form-group-DISABLED {{ $parentClass or null }}">
    <label
        for="{{ $id or $name }}"
        class="bmd-label {{ $labelClass or null }}">
        {{ $slot }}
    </label>

    <select
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="custom-select {{ $class or null }} @if($errors->has($name)) is-invalid @endif"
        {{ $attributes or null }}>

        @foreach($options as $value => $option)
            <?php
            $value = (is_array($options)) ? $value : $option['id'];
            $selectCurrent = (is_scalar($selected)) ? $selected == $value : $selected['id'] == $value;
            ?>
            <option value="{{ $value }}" @if($selectCurrent) selected @endif>
                {{ $option }}
            </option>
        @endforeach
    </select>

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif

    @isset($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endisset
</div>
