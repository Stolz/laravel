@input(['name' => 'name', 'value' => old('name', $country['name']), 'attributes' => 'required autofocus maxlength=255'])
    {{ _('Name') }}
@endinput

@input(['name' => 'code', 'value' => old('code', $country['code']), 'attributes' => 'required pattern=[A-Z]{2} minlength=2 maxlength=2'])
    {{ _('Code') }}
    @slot('hint')
        {{ _('May only contain uppercase letters') }}
    @endslot
@endinput
