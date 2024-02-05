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
        Schema::create('business_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('request_id');
            $table->foreign('patient_id')->references('id')->on('patient_details');
            $table->foreign('request_id')->references('id')->on('request');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('phone');
            $table->string('business_name');
            $table->integer('case_number');
            $table->string('email');
            $table->integer('room');
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
        Schema::dropIfExists('business_request');
    }
};
