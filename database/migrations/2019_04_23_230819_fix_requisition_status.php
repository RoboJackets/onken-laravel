<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRequisitionStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn('state');
        });
        Schema::table('requisitions', function (Blueprint $table) {
            $table->enum('state', ['draft', 'pending_approval', 'approved', 'ordered', 'partially_shipped', 'fully_shipped', 'partially_received', 'fully_received']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->enum('state', ['Draft', 'Pending Approval', 'Approved', 'Ordered', 'Partially Shipped', 'Fully Shipped', 'Partially Received', 'Fully Received']);
        });
    }
}
