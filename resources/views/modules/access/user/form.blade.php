@select(['name' => 'role', 'options' => $roles, 'selected' => old('role', $user['role']), 'attributes' => 'required'])
    {{ _('Role') }}
@endselect

@input(['name' => 'name', 'value' => old('name', $user['name']), 'attributes' => 'required autofocus autocomplete=off'])
    {{ _('Name') }}
@endinput

@input(['type' => 'email', 'name' => 'email', 'value' => old('email', $user['email']), 'attributes' => 'required autocomplete=off'])
    {{ _('E-mail') }}
@endinput
