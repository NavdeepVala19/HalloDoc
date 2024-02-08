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
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_type_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('business_type_id')->references('id')->on('business_type');
            $table->foreign('status_id')->references('id')->on('status');
            $table->string('business_name');
            $table->string('contact');
            $table->string('email');
            $table->integer('fax_number');
            $table->integer('zipcode');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};
