<div class="form-group-select {{ $parentClass or null }}">
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

        <?php
            // Convert selected value to scalar (or array of scalars when multiple values)
            if ($selected instanceof \App\Models\Model)
               $selected = $selected['id'];
            elseif ($selected instanceof \IteratorAggregate) // This applies to both Doctrine and Laravel collections
               $selected = collect(iterator_to_array($selected))->map->getId()->all();
        ?>
        @foreach($options as $value => $option)
            <?php
            // Convert current value to scalar
            if ($option instanceof \App\Models\Model)
                $value = $option['id'];

            // Check if current value should be selected
            $selectCurrent = (is_array($selected)) ? in_array($value, $selected) : $selected == $value;
            ?>
            <option value="{{ $value }}" @if($selectCurrent) selected @endif @empty($value) disabled @endempty>
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
