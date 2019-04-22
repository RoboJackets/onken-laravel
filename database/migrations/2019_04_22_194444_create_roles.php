<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class CreateRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        $r_admin = Role::firstOrCreate(['name' => 'admin']);
        $r_member = Role::firstOrCreate(['name' => 'member']);

        $p_user_read = Permission::firstOrCreate(['name' => 'read-users']);
        $p_user_read_own = Permission::firstOrCreate(['name' => 'read-users-own']);

        $r_admin->syncPermissions(Permission::all());
        $r_member->syncPermissions(['read-users-own']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::all()->delete();
        Role::all()->delete();
    }
}
