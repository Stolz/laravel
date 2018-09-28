@unless($doPermissions)
    <div class="row">
        <div class="col-md">
            @input(['name' => 'name', 'value' => old('name', $role['name']), 'attributes' => 'required autofocus maxlength=255'])
                {{ _('Name') }}
            @endinput
        </div>
        <div class="col-md">
            @input(['name' => 'description', 'value' => old('description', $role['description']), 'attributes' => 'maxlength=255'])
                {{ _('Description') }}
            @endinput
        </div>
    </div>
@else
    @if($errors->has('permissions'))
        @alert(['type' => 'error'])
            {{ $errors->first('permissions') }}
        @endalert
    @endif

    @foreach($permissionsTree as $module)
        @card(['class' => 'card-collapsed'])
            @slot('header')
                @checkbox(['name' => "permissions[{$module['name']}]", 'checked' => old("permissions[{$module['name']}]", $selectedPermissions->contains($module['name']))])
                    <div class="card-title">{{ $module['description'] }}</div>
                @endcheckbox
                <div class="card-options">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                </div>
            @endslot

            <div class="row">
                @foreach($module['categories'] as $category => $permissions)
                    <div class="col-6 col-lg-4 col-xl-3">
                        <div class="form-fieldset p-2">
                            <div class="form-label">{{ _($category) }}</div>
                            <div class="custom-switches-stacked">
                                @foreach($permissions as $permission)
                                    @checkbox([
                                        'parentClass' => 'custom-control-inline',
                                        'name' => "permissions[{$permission['name']}]",
                                        'checked' => old("permissions[{$permission['name']}]", $selectedPermissions->contains($permission['name']))])
                                        {{ $permission['description'] ?? $permission['name'] }}
                                    @endcheckbox
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endcard
    @endforeach
@endunless
