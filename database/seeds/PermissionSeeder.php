<?php

use Illuminate\Database\Seeder;
use ProcessMaker\Models\User;
use ProcessMaker\Models\Group;
use ProcessMaker\Models\GroupMember;
use ProcessMaker\Models\Permission;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        'archive-processes',
        'create-categories',
        'create-comments',
        'create-environment_variables',
        'create-groups',
        'create-processes',
        'create-screens',
        'create-scripts',
        'create-users',
        'delete-categories',
        'delete-comments',
        'delete-environment_variables',
        'delete-groups',
        'delete-screens',
        'delete-scripts',
        'delete-users',
        'edit-categories',
        'edit-comments',
        'edit-environment_variables',
        'edit-groups',
        'edit-processes',
        'edit-screens',
        'edit-scripts',
        'edit-users',
        'export-processes',
        'import-processes',
        'view-all_requests',
        'view-categories',
        'view-comments',
        'view-environment_variables',
        'view-groups',
        'view-processes',
        'view-screens',
        'view-scripts',
        'view-users',
        'create-files',
        'view-files',
        'edit-files',
        'delete-files',
        'create-notifications',
        'view-notifications',
        'edit-notifications',
        'delete-notifications',
        'create-task_assignments',
        'view-task_assignments',
        'edit-task_assignments',
        'delete-task_assignments',
        'create-auth_clients',
        'view-auth_clients',
        'edit-auth_clients',
        'delete-auth_clients',
        'edit-request_data',
        'edit-task_data',
    ];
    
    public function run($seedUser = null)
    {
        if (Permission::count() === 0) {
            foreach ($this->permissions as $permissionString) {
                $permission = factory(Permission::class)->create([
                    'title' => ucwords(preg_replace('/(\-|_)/', ' ',
                            $permissionString)),
                    'name' => $permissionString,
                ]);
            }
        }

        $permissions = Permission::all()->pluck('id');

        if ($seedUser) {
            $seedUser->permissions()->attach($permissions);
            $seedUser->save();
        }
    }
}
