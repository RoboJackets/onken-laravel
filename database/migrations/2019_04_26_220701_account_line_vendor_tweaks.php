<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountLineVendorTweaks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_lines', function (Blueprint $table) {
            $table->integer('line_number');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('billing_address')->nullable()->change();
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->date('start_date')->default('1970-01-01');
            $table->date('end_date')->default('1970-01-01');
        });
        Schema::table('requisition_lines', function (Blueprint $table) {
            $table->integer('quantity_received')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition_lines', function (Blueprint $table) {
            $table->integer('quantity_received')->change();
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
        Schema::table('fiscal_years', function (Blueprint $table) {
            $table->dateTime('start_date')->default('1970-01-01');
            $table->dateTime('end_date')->default('1970-01-01');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('billing_address')->change();
        });
        Schema::table('account_lines', function (Blueprint $table) {
            $table->dropColumn('line_number');
        });
    }
}
