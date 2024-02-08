<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 
     * this table have 4 type of request
     * 1.patient
     * 2.family
     * 3.conceirege
     * 4.business
     */
    public function up(): void
    {
        Schema::create('patient_request_type', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patient_details');
            $table->string('request_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_request_type');
    }
};
