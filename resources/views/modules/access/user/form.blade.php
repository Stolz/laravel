<div class="row">
    <div class="col">
        @select(['name' => 'role', 'options' => $roles, 'selected' => old('role', $user['role']), 'attributes' => 'required'])
            {{ _('Role') }}
        @endselect

        @input(['name' => 'name', 'value' => old('name', $user['name']), 'attributes' => 'required autofocus maxlength=255'])
            {{ _('Name') }}
        @endinput
    </div>

    <div class="col">
        @input(['type' => 'email', 'name' => 'email', 'value' => old('email', $user['email']), 'attributes' => 'required maxlength=255'])
            {{ _('E-mail') }}
        @endinput

        @input(['type' => 'password', 'name' => 'password', 'attributes' => ($isUpdate) ? 'autocomplete=new-password maxlength=255' : 'required autocomplete=new-password maxlength=255'])
            {{ _('Password') }}
            @slot('help')
                {{ ($isUpdate) ? _("Leave it blank if you don't want to update the password") : sprintf(_('Password must be at least %d characters long'), $minPasswordLength) }}
            @endslot
        @endinput
    </div>
</div>



