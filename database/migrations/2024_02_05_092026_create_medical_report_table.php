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
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('location');
            $table->date('service_date');
            $table->date('date_of_birth');
            $table->integer('mobile');
            $table->string('present_illness_history');
            $table->string('medical_history');
            $table->string('medications');
            $table->string('allergies');
            $table->integer('temperature');
            $table->integer('heart_rate');
            $table->integer('repository_rate');
            $table->integer('sis_BP');
            $table->integer('dia_BP');
            $table->integer('oxygen');
            $table->string('pain');
            $table->string('heent');
            $table->string('cv');
            $table->string('chest');
            $table->string('abd');
            $table->string('extr');
            $table->string('skin');
            $table->string('neuro');
            $table->string('other');
            $table->string('diagnosis');
            $table->string('treatment_plan');
            $table->string('medication_dispensed');
            $table->string('procedure');
            $table->string('followUp');
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
