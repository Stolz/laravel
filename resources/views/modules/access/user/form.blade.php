<div class="row">
    <div class="col-lg">
        @select(['name' => 'role', 'options' => $roles, 'selected' => old('role', $user['role']), 'attributes' => 'required'])
            {{ _('Role') }}
        @endselect

        @input(['name' => 'name', 'value' => old('name', $user['name']), 'attributes' => 'required autofocus maxlength=255'])
            {{ _('Name') }}
        @endinput
    </div>

    <div class="col-lg">
        @input(['type' => 'email', 'name' => 'email', 'value' => old('email', $user['email']), 'icon' => 'fe fe-at-sign', 'attributes' => 'required maxlength=255'])
            {{ _('E-mail') }}
        @endinput

        @input(['type' => 'password', 'name' => 'password', 'icon' => 'fe fe-lock', 'attributes' => ($isUpdate) ? 'autocomplete=new-password maxlength=255' : 'required autocomplete=new-password maxlength=255'])
            {{ _('Password') }}
            @slot('help')
                {{ ($isUpdate) ? _("Leave it blank if you don't want to update the password") : sprintf(_('Password must be at least %d characters long'), $minPasswordLength) }}
            @endslot
        @endinput
    </div>

</div>

@select(['name' => 'timezone', 'options' => $timezones, 'selected' => old('timezone', $user['timezone']), 'attributes' => 'required'])
    {{ _('Time zone') }}
@endselect


