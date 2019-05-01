<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorOrderModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_reference')->nullable();
            $table->string('note')->nullable();
            $table->dateTime('placed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('requisitions', function (Blueprint $table) {
            $table->unsignedInteger('vendor_order_id')->nullable();

            $table->foreign('vendor_order_id')->references('id')->on('requisitions');
        });

        app()['cache']->forget('spatie.permission.cache');

        Permission::firstOrCreate(['name' => 'create-vendor-orders']);
        Permission::firstOrCreate(['name' => 'read-vendor-orders']);
        Permission::firstOrCreate(['name' => 'update-vendor-orders']);
        Permission::firstOrCreate(['name' => 'delete-vendor-orders']);

        $r_admin = Role::findByName('admin');
        $r_member = Role::findByName('member');
        $r_viewer = Role::findByName('viewer');

        $r_admin->givePermissionTo('create-vendor-orders');
        $r_admin->givePermissionTo('read-vendor-orders');
        $r_admin->givePermissionTo('update-vendor-orders');
        $r_admin->givePermissionTo('delete-vendor-orders');
        $r_member->givePermissionTo('read-vendor-orders');
        $r_viewer->givePermissionTo('read-vendor-orders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::findByName('create-vendor-orders')->delete();
        Permission::findByName('read-vendor-orders')->delete();
        Permission::findByName('update-vendor-orders')->delete();
        Permission::findByName('delete-vendor-orders')->delete();

        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropForeign('vendor_order_id');

            $table->dropColumn('vendor_order_id');
        });

        Schema::dropIfExists('vendor_orders');
    }
}
