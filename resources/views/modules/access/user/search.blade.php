<h4 class="display-4">{{ _('Search') }}</h4>

<form method="get" role="search" autocomplete="off">

    @select(['name' => 'search[role]', 'options' => $roles, 'selected' => request()->input('search.role')])
        {{ _('Role') }}
    @endselect

    @input(['name' => 'search[name]', 'value' => request()->input('search.name'), 'attributes' => 'maxlength=255'])
        {{ _('Name') }}
    @endinput

    @input(['name' => 'search[email]', 'value' => request()->input('search.email'), 'attributes' => 'maxlength=255'])
        {{ _('E-mail') }}
    @endinput

    <button type="submit" class="btn btn-dark btn-raised btn-block">
        {{ _('Search') }}
    </button>

    @checkbox(['name' => 'search[all]', 'checked' => request()->has('search.all'), 'attributes' => 'value=1'])
        {{ _('Include inactive') }}
    @endcheckbox

</form>
