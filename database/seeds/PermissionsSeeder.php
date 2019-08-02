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
                'description' => _('Use the "Access" module. Without this permission none of the permissions in this module will be available'),
                'categories' => [
                    _('Role') => [
                        ['name' => 'role-list', 'description' => _('See a list of multiple roles')],
                        ['name' => 'role-view', 'description' => _('See all details of a single role')],
                        ['name' => 'role-create', 'description' => _('Add a new role')],
                        ['name' => 'role-update', 'description' => _('Edit an existing role')],
                        ['name' => 'role-delete', 'description' => _('Remove an existing role')],
                    ],
                    _('User') => [
                        ['name' => 'user-list', 'description' => _('See a list of multiple users')],
                        ['name' => 'user-view', 'description' => _('See all details of a single user')],
                        ['name' => 'user-create', 'description' => _('Add a new user')],
                        ['name' => 'user-update', 'description' => _('Edit an existing user')],
                        ['name' => 'user-delete', 'description' => _('Remove an existing user')],
                    ],
                ],
            ],
            [
                'name' => 'use-master-module',
                'description' => _('Use the "Master" module. Without this permission none of the permissions in this module will be available'),
                'categories' => [
                    _('Announcement') => [
                        ['name' => 'announcement-list', 'description' => _('See a list of multiple announcements')],
                        ['name' => 'announcement-view', 'description' => _('See all details of a single announcement')],
                        ['name' => 'announcement-create', 'description' => _('Add a new announcement')],
                        ['name' => 'announcement-update', 'description' => _('Edit an existing announcement')],
                        ['name' => 'announcement-delete', 'description' => _('Remove an existing announcement')],
                    ],
                    _('Country') => [
                        ['name' => 'country-list', 'description' => _('See a list of multiple countries')],
                        ['name' => 'country-view', 'description' => _('See all details of a single country')],
                        ['name' => 'country-create', 'description' => _('Add a new country')],
                        ['name' => 'country-update', 'description' => _('Edit an existing country')],
                        ['name' => 'country-delete', 'description' => _('Remove an existing country')],
                    ],
                ],
            ],
        ];
    }

   /**
    * Get permissions as a flat list.
    *
    * @return array
    */
    public static function list(): array
    {
        $allPermissions = [];

        foreach (self::tree() as $module) {
            $allPermissions[$module['name']] = $module['description'];

            foreach ($module['categories'] as $category => $permissions) {
                foreach ($permissions as $permission) {
                    $allPermissions[$permission['name']] = $permission['description'];
                }
            }
        }

        return $allPermissions;
    }

   /**
    * Seed the application's database.
    *
    * @return void
    */
    public function run()
    {
        foreach (self::list() as $name => $description) {
            $permission = Permission::make()->setName($name)->setDescription($description);
            $this->permissionRepository->create($permission);
        }
    }
}
