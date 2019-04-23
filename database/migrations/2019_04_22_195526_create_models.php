<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('nationality');
            $table->string('billing_address');
            $table->integer('gt_vendor_id')->nullable();
            $table->string('status')->nullable();
            $table->string('sales_contact')->nullable();
            $table->string('customer')->nullable();
            $table->boolean('web_account_exists');
            $table->string('website')->nullable();
            $table->string('part_url_schema')->nullable();
            $table->boolean('shipping_quote_required');
            $table->boolean('tax_exempt');
            $table->string('requisition_guidance')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('vendor_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('user_id');
            $table->string('note');
            $table->unsignedInteger('parent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('vendor_notes');
        });
        Schema::create('vendor_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id');
            $table->string('tag');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_tags');
        Schema::dropIfExists('vendor_notes');
        Schema::dropIfExists('vendors');
    }
}
