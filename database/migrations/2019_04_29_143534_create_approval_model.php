<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requisition_id');
            $table->usignedInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('requisition_id')->references('id')->on('requisitions');
            $table->foreign('user_id')->references('id')->on('users');
        });

        app()['cache']->forget('spatie.permission.cache');

        Permission::firstOrCreate(['name' => 'create-approvals']);
        Permission::firstOrCreate(['name' => 'read-approvals']);
        Permission::firstOrCreate(['name' => 'delete-approvals']);

        $r_admin = Role::findByName('admin');
        $r_member = Role::findByName('member');
        $r_viewer = Role::findByName('viewer');

        $r_admin->givePermissionTo('create-approvals');
        $r_admin->givePermissionTo('read-approvals');
        $r_admin->givePermissionTo('delete-approvals');
        $r_member->givePermissionTo('read-approvals');
        $r_viewer->givePermissionTo('read-approvals');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::findByName('create-approvals')->delete();
        Permission::findByName('read-approvals')->delete();
        Permission::findByName('delete-approvals')->delete();

        Schema::dropIfExists('approvals');
    }
}
