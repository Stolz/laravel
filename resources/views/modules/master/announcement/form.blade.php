<div class="row">
    <div class="col-lg">
        @input(['name' => 'name', 'value' => old('name', $announcement['name']), 'attributes' => 'required autofocus maxlength=255'])
            {{ _('Name') }}
        @endinput
    </div>

    <div class="col-lg">
        @radios(['name' => 'active', 'options' => [_('No'), _('Yes')], 'selected' => old('active', $announcement['active']), 'attributes' => 'required'])
            @slot('help')
                {{ _('When active, the announcement will be shown on top of every page') }}
            @endslot
            {{ _('Active') }}
        @endradios
    </div>
</div>

@textarea(['name' => 'description', 'value' => old('description', $announcement['description'])])
    @slot('help')
        {!! sprintf(_('Description accepts %sMarkdown%s format'), '<a href="https://en.wikipedia.org/wiki/Markdown#Example" target="_blank">', '</a>') !!}
    @endslot

    {{ _('Description') }}
@endtextarea

