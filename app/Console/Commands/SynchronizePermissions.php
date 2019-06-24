<?php

namespace App\Console\Commands;

use App\Models\Permission;

class SynchronizePermissions extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'permissions:sync';

   /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Synchronize list of available role permissions';

   /**
    * Execute the console command.
    *
    * @param  \App\Repositories\Contracts\PermissionRepository $permissionRepository
    * @return mixed
    */
    public function handle(\App\Repositories\Contracts\PermissionRepository $permissionRepository)
    {
        // Compile list of permissions
        $permissions = collect();
        foreach (\PermissionsSeeder::list() as $name => $description) {
            $permission = Permission::make(['name' => $name, 'description' => $description]);
            $permissions->push($permission);
        }

        // Sync with database
        if ($permissionRepository->sync($permissions))
           return $this->info('Permissions successfully synchronized');

        return $this->error('Unable to synchronize permissions');
    }
}
