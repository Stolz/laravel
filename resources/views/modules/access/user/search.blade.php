<h4 class="display-4">{{ _('Search') }}</h4>

<form method="get" role="form">

    @select(['name' => 'search[role]', 'options' => $roles, 'selected' => request()->input('search.role')])
        {{ _('Role') }}
    @endinput

    @input(['name' => 'search[name]', 'value' => request()->input('search.name')])
        {{ _('Name') }}
    @endinput

    @input(['name' => 'search[email]', 'value' => request()->input('search.email')])
        {{ _('E-mail') }}
    @endinput

    <button type="submit" class="btn btn-dark btn-raised btn-block">
        {{ _('Search') }}
    </button>

</form>
