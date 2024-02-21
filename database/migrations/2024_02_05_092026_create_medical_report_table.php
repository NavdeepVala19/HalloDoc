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
        Schema::create('medical_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->nullable();
            $table->foreign('request_id')->references('id')->on('request');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('location')->nullable();
            $table->date('service_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mobile')->nullable();
            $table->string('present_illness_history')->nullable();
            $table->string('medical_history')->nullable();
            $table->string('medications')->nullable();
            $table->string('allergies')->nullable();
            $table->integer('temperature')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->integer('repository_rate')->nullable();
            $table->integer('sis_BP')->nullable();
            $table->integer('dia_BP')->nullable();
            $table->integer('oxygen')->nullable();
            $table->string('pain')->nullable();
            $table->string('heent')->nullable();
            $table->string('cv')->nullable();
            $table->string('chest')->nullable();
            $table->string('abd')->nullable();
            $table->string('extr')->nullable();
            $table->string('skin')->nullable();
            $table->string('neuro')->nullable();
            $table->string('other')->nullable();
            $table->string('diagnosis')->nullable();
            $table->string('treatment_plan')->nullable();
            $table->string('medication_dispensed')->nullable();
            $table->string('procedure')->nullable();
            $table->string('followUp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_report');
    }
};
