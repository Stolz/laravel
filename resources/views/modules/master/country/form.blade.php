<div class="row">
    <div class="col-md">
        @input(['name' => 'name', 'value' => old('name', $country['name']), 'attributes' => 'required autofocus maxlength=255'])
            {{ _('Name') }}
        @endinput
    </div>

    <div class="col-md">
        @input(['name' => 'code', 'value' => old('code', $country['code']), 'attributes' => 'required pattern=[A-Z]{2} minlength=2 maxlength=2'])
            {{ _('Code') }}
            @slot('help')
                {{ _('Code must contain only uppercase letters') }}
            @endslot
        @endinput
    </div>
</div>
