@card
    @slot('header')
        <div class="card-title">{{ _('Search') }}</div>
        <div class="card-options">
            <a href="#" class="hide-side" title="{{ _('Hide search') }}"><i class="fe fe-search"></i></a>
            <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen" title="{{ _('Toggle full screen') }}"><i class="fe fe-maximize"></i></a>
        </div>
    @endslot

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

        <div class="form-footer">
            <button type="submit" class="btn btn-dark btn-block">
                <i class="fe fe-search"></i>
                {{ _('Search') }}
            </button>

            <div class="d-flex justify-content-center">
                @checkbox(['name' => 'search[all]', 'checked' => request()->has('search.all'), 'attributes' => 'value=1'])
                    {{ _('Include inactive') }}
                @endcheckbox
            </div>
        </div>
    </form>
@endcard
