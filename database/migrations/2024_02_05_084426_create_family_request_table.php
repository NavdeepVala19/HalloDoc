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
        Schema::create('family_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('document_id');
            $table->foreign('patient_id')->references('id')->on('patient_details');
            $table->foreign('request_id')->references('id')->on('request');
            $table->foreign('document_id')->references('id')->on('documents');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('mobile');
            $table->string('relation');
            $table->string('upload_file');
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
        Schema::dropIfExists('family_request');
    }
};
