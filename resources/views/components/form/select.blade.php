<div class="form-groupa">
    <label
        for="{{ $id or $name }}"
        class="bmd-label-floating {{ $labelClass or null }}">
        {{ $slot }}
    </label>

    <select
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="custom-select {{ $class or null }} @if($errors->has($name)) is-invalid @endif"
        {{ $attributes or null }}>

        @foreach($options as $option)
            <?php $selectCurrent = (is_scalar($selected)) ? ($selected == $option['id']) : ($selected['id'] == $option['id']); ?>
            <option value="{{ $option['id'] }}" @if($selectCurrent) selected @endif>
                {{ $option }}
            </option>
        @endforeach
    </select>

    @if($errors->has($name))
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>
