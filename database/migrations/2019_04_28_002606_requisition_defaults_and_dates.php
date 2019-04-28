<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequisitionDefaultsAndDates extends Migration
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
            $table->dropColumn('receive_by');
        });
        Schema::table('requisitions', function (Blueprint $table) {
            // Add default to state column, and make receive_by a date instead of a datetime
            $table->enum('state', ['draft', 'pending_approval', 'approved', 'ordered', 'partially_shipped', 'fully_shipped', 'partially_received', 'fully_received'])->default('draft');
            $table->date('receive_by')->nullable();
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
            $table->dropColumn('receive_by');
        });
        Schema::table('requisitions', function (Blueprint $table) {
            $table->enum('state', ['draft', 'pending_approval', 'approved', 'ordered', 'partially_shipped', 'fully_shipped', 'partially_received', 'fully_received']);
            $table->dateTime('receive_by')->nullable();
        });
    }
}
