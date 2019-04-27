<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class CreatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        // This is not really necessary since there's nothing to see you can't see on Apiary
        Permission::firstOrCreate(['name' => 'read-users-own'])->delete();

        Permission::firstOrCreate(['name' => 'access-nova']);

        Permission::firstOrCreate(['name' => 'create-accounts']);
        Permission::firstOrCreate(['name' => 'read-accounts']);
        Permission::firstOrCreate(['name' => 'update-accounts']);
        Permission::firstOrCreate(['name' => 'delete-accounts']);

        Permission::firstOrCreate(['name' => 'create-account-lines']);
        Permission::firstOrCreate(['name' => 'read-account-lines']);
        Permission::firstOrCreate(['name' => 'update-account-lines']);
        Permission::firstOrCreate(['name' => 'delete-account-lines']);

        Permission::firstOrCreate(['name' => 'create-fiscal-years']);
        Permission::firstOrCreate(['name' => 'read-fiscal-years']);
        Permission::firstOrCreate(['name' => 'update-fiscal-years']);
        Permission::firstOrCreate(['name' => 'delete-fiscal-years']);

        Permission::firstOrCreate(['name' => 'create-projects']);
        Permission::firstOrCreate(['name' => 'read-projects']);
        Permission::firstOrCreate(['name' => 'update-projects']);
        Permission::firstOrCreate(['name' => 'delete-projects']);

        Permission::firstOrCreate(['name' => 'create-requisitions']);
        Permission::firstOrCreate(['name' => 'read-requisitions']);
        Permission::firstOrCreate(['name' => 'update-requisitions']);
        Permission::firstOrCreate(['name' => 'update-requisitions-locked']);
        Permission::firstOrCreate(['name' => 'delete-requisitions']);
        Permission::firstOrCreate(['name' => 'delete-requisitions-locked']);

        Permission::firstOrCreate(['name' => 'create-requisition-lines']);
        Permission::firstOrCreate(['name' => 'read-requisition-lines']);
        Permission::firstOrCreate(['name' => 'update-requisition-lines']);
        Permission::firstOrCreate(['name' => 'update-requisition-lines-locked']);
        Permission::firstOrCreate(['name' => 'delete-requisition-lines']);
        Permission::firstOrCreate(['name' => 'delete-requisition-lines-locked']);

        // Read already exists, and create and update don't make sense
        Permission::firstOrCreate(['name' => 'delete-users']);
        Permission::firstOrCreate(['name' => 'read-users_detailed']);
        Permission::firstOrCreate(['name' => 'update-user-permissions']);

        Permission::firstOrCreate(['name' => 'create-vendors']);
        Permission::firstOrCreate(['name' => 'read-vendors']);
        Permission::firstOrCreate(['name' => 'update-vendors']);
        Permission::firstOrCreate(['name' => 'delete-vendors']);
        Permission::firstOrCreate(['name' => 'read-vendors_detailed']);

        Permission::firstOrCreate(['name' => 'create-vendor-notes']);
        Permission::firstOrCreate(['name' => 'read-vendor-notes']);
        Permission::firstOrCreate(['name' => 'update-vendor-notes']);
        Permission::firstOrCreate(['name' => 'update-vendor-notes-own']);
        Permission::firstOrCreate(['name' => 'delete-vendor-notes']);
        Permission::firstOrCreate(['name' => 'delete-vendor-notes-own']);

        Permission::firstOrCreate(['name' => 'create-vendor-tags']);
        Permission::firstOrCreate(['name' => 'read-vendor-tags']);
        Permission::firstOrCreate(['name' => 'update-vendor-tags']);
        Permission::firstOrCreate(['name' => 'delete-vendor-tags']);

        $r_admin = Role::firstOrCreate(['name' => 'admin']);
        $r_member = Role::firstOrCreate(['name' => 'member']);
        $r_viewer = Role::firstOrCreate(['name' => 'viewer']);

        $r_viewer_perms = [
            'read-accounts', 'read-account-lines', 'read-fiscal-years', 'read-requisitions', 'read-requisition-lines',
            'read-users', 'read-vendors', 'read-vendor-notes', 'read-vendor-tags',
        ];
        $r_member_perms = array_merge($r_viewer_perms, [
            'create-requisitions', 'update-requisitions', 'delete-requisitions', 'create-requisition-lines',
            'update-requisition-lines', 'delete-requisition-lines', 'create-vendors', 'create-vendor-notes',
            'update-vendor-notes-own', 'delete-vendor-notes-own', 'create-vendor-tags',
        ]);
        $r_viewer_perms[] = 'access-nova'; // Add after since members don't get this

        $r_admin->syncPermissions(Permission::all());
        $r_member->syncPermissions($r_member_perms);
        $r_viewer->syncPermissions($r_viewer_perms);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::where('name', '!=', 'read-users')->delete();
        Permission::firstOrCreate(['name' => 'read-users-own']);

        Role::where('name', 'viewer')->delete();
        Role::firstOrCreate(['name' => 'admin'])->syncPermissions(Permission::all());
        Role::firstOrCreate(['name' => 'member'])->syncPermissions(['read-users-own']);
    }
}
