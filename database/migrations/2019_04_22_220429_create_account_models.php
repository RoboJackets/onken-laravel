<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('name');
            $table->boolean('active');

            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('fiscal_year_id');
            $table->string('workday_number')->nullable();
            $table->string('sga_bill_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years');
        });
        Schema::create('account_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('account_id');
            $table->decimal('amount', 8, 2);
            $table->unsignedInteger('approver_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('approver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_lines');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('fiscal_years');
    }
}
