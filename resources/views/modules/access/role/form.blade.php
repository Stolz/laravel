@input(['name' => 'name', 'value' => old('name', $role['name']), 'attributes' => 'required autofocus autocomplete=off'])
    {{ _('Name') }}
@endinput

@input(['name' => 'description', 'value' => old('description', $role['description']), 'attributes' => 'autocomplete=off'])
    {{ _('Description') }}
@endinput

<h3 class="display-4">{{ _('Permissions') }}</h3>
@if($errors->has('permissions'))
    <div class="invalid-feedback">{{ $errors->first('permissions') }}</div>
@endif

@foreach($permissionsTree as $module)
    @checkbox([
        'name' => "permissions[{$module['name']}]", 'checked' => old("permissions[{$module['name']}]", $selectedPermissions->contains($module['name']))])
        <h4>{{ _($module['description']) }}</h4>
    @endcheckbox

    <div class="card-deck">
        @foreach($module['categories'] as $category => $permissions)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ _($category) }}</h5>
                    <div class="card-text">
                        @foreach($permissions as $permission)
                            @checkbox([
                                'parentClass' => 'custom-control-inline',
                                'name' => "permissions[{$permission['name']}]",
                                'checked' => old("permissions[{$permission['name']}]", $selectedPermissions->contains($permission['name']))])
                                {{ $permission['description'] or $permission['name'] }}
                            @endcheckbox
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <hr/>
@endforeach
