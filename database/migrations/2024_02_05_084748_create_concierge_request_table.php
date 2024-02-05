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
        Schema::create('concierge_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('request_id');
            $table->foreign('patient_id')->references('id')->on('patient_details');
            $table->foreign('request_id')->references('id')->on('request');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('hotel_name');
            $table->string('email');
            $table->string('street');
            $table->integer('zipcode');
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
        Schema::dropIfExists('concierge_request');
    }
};
