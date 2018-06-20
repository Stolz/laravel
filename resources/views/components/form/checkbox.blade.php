<div class="custom-control custom-checkbox {{ $parentClass or null }}">
    <input
        type="checkbox"
        id="{{ $id or $name }}"
        name="{{ $name }}"
        class="custom-control-input {{ $class or null }}"
        @isset($checked) checked @endisset>
    <label
        for="{{ $id or $name }}"
        class="custom-control-label {{ $labelClass or null }}">
        {{ $slot }}
    </label>
</div>
