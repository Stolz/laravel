@input(['name' => 'name', 'value' => old('name', $role['name']), 'attributes' => 'required autofocus autocomplete=off'])
    {{ _('Name') }}
@endinput

@input(['name' => 'description', 'value' => old('description', $role['description']), 'attributes' => 'autocomplete=off'])
    {{ _('Description') }}
@endinput
