<?php

use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Get permission grouped by module and category.
     *
     * @return array
     */
    public static function tree(): array
    {
        return [
            [
                'name' => 'use-access-module',
                'description' => _('Access module'),
                'categories' => [
                    _('Role') => [
                        ['name' => 'role-list', 'description' => _('List')],
                        ['name' => 'role-view', 'description' => _('View')],
                        ['name' => 'role-create', 'description' => _('Create')],
                        ['name' => 'role-update', 'description' => _('Update')],
                        ['name' => 'role-delete', 'description' => _('Delete')],
                    ],
                    _('User') => [
                        ['name' => 'user-list', 'description' => _('List')],
                        ['name' => 'user-view', 'description' => _('View')],
                        ['name' => 'user-create', 'description' => _('Create')],
                        ['name' => 'user-update', 'description' => _('Update')],
                        ['name' => 'user-delete', 'description' => _('Delete')],
                    ],
                ],
            ],
            [
                'name' => 'use-master-module',
                'description' => _('Master module'),
                'categories' => [
                    _('Country') => [
                        ['name' => 'country-list', 'description' => _('List')],
                        ['name' => 'country-view', 'description' => _('View')],
                        ['name' => 'country-create', 'description' => _('Create')],
                        ['name' => 'country-update', 'description' => _('Update')],
                        ['name' => 'country-delete', 'description' => _('Delete')],
                    ],
                ],
            ],
        ];
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::tree() as $module) {
            $permission = Permission::make($module);
            $this->permissionRepository->create($permission);

            foreach ($module['categories'] as $category => $permissions) {
                foreach ($permissions as $permission) {
                    $permission = Permission::make($permission);
                    $this->permissionRepository->create($permission);
                }
            }
        }
    }
}
