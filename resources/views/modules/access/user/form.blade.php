@select(['name' => 'role', 'options' => $roles, 'selected' => old('role', $user['role']), 'attributes' => 'required'])
    {{ _('Role') }}
@endselect

@input(['name' => 'name', 'value' => old('name', $user['name']), 'attributes' => 'required autofocus maxlength=255'])
    {{ _('Name') }}
@endinput

@input(['type' => 'email', 'name' => 'email', 'value' => old('email', $user['email']), 'attributes' => 'required maxlength=255'])
    {{ _('E-mail') }}
@endinput
