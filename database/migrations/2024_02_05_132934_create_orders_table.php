<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profession_id');
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('profession_id')->references('id')->on('profession');
            $table->foreign('vendor_id')->references('id')->on('vendor');
            $table->string('business_contact');
            $table->string('email');
            $table->integer('fax_number');
            $table->string('order_details');
            $table->integer('no_of_refills');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
