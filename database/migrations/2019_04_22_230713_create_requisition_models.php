<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('fiscal_year_id');
            $table->enum('state', ['Draft', 'Pending Approval', 'Approved', 'Ordered', 'Partially Shipped', 'Fully Shipped', 'Partially Received', 'Fully Received']);
            $table->unsignedInteger('technical_contact_id');
            $table->unsignedInteger('finance_contact_id');
            $table->dateTime('receive_by')->nullable();
            $table->string('exception')->nullable();
            $table->unsignedInteger('exception_author_id')->nullable();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_years');
            $table->foreign('technical_contact_id')->references('id')->on('users');
            $table->foreign('finance_contact_id')->references('id')->on('users');
            $table->foreign('exception_author_id')->references('id')->on('users');
        });
        Schema::create('requisition_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requisition_id');
            $table->string('sku')->nullable();
            $table->string('sku_url')->nullable();
            $table->string('description');
            $table->double('cost', 8, 4);
            $table->integer('quantity');
            $table->integer('quantity_received');
            $table->string('note')->nullable();
            $table->unsignedInteger('account_line_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('requisition_id')->references('id')->on('requisitions');
            $table->foreign('account_line_id')->references('id')->on('account_lines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisition_lines');
        Schema::dropIfExists('requisitions');
    }
}
