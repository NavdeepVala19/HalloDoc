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
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_type_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('case_tag');
            $table->foreign('request_type_id')->references('id')->on('patient_request_type');
            $table->foreign('status')->references('id')->on('status');
            $table->foreign('provider_id')->references('id')->on('provider');
            $table->foreign('case_tag')->references('id')->on('case_tag');
            $table->foreign('user_id')->references('id')->on('allusers');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            // $table->string('declined_by');
            // $table->boolean('is_urgent_email_sent');
            // $table->string('relation_name');
            // $table->string('case_number');
            $table->integer('mobile');
            $table->string('confirmation_no');
            $table->date('last_wellness_date');
            $table->enum('call_type',['house_call','consult']);
            $table->date('last_reservation_date');
            $table->boolean('completed_by_physician');
            $table->date('accepted_date');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};
