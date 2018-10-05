<div class="form-group {{ $parentClass ?? null }}">
    <label
        for="{{ $id ?? $name }}"
        class="form-label {{ $labelClass ?? null }}">
        {{ $slot }}
        @if(isset($attributes) and str_contains($attributes, 'required'))
            <span class="form-required">*</span>
        @endif
    </label>

    <select
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        class="form-control custom-select {{ $class ?? null }} @if($errors->has($name)) is-invalid state-invalid @elseif($errors->count()) is-valid state-valid @endif"
        {{ $attributes ?? null }}>

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
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif

    @isset($help)
        <div class="small text-muted">{{ $help }}</div>
    @endisset
</div>
