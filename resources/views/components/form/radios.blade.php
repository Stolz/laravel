<div class="form-group {{ $parentClass or null }}">
    <label
        for="{{ $id or $name }}"
        class="bmd-label {{ $labelClass or null }}">
        {{ $slot }}
    </label>

    @foreach($options as $value => $option)
        <?php
        $value = (is_array($options)) ? $value : $option['id'];
        $selectCurrent = (is_scalar($selected)) ? $selected == $value : $selected['id'] == $value;
        ?>
        <div class="custom-control custom-radio">
            <input
                type="radio"
                id="{{ $id or $name }}{{ $value }}"
                name="{{ $name }}"
                class="custom-control-input @if($errors->has($name)) is-invalid @endif"
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
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>


