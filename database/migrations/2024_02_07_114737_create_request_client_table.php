<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    /**
     * Users detail will be stored here 
     */
    public function up(): void
    {
        Schema::create('request_client', function (Blueprint $table) {
            $table->id();
            // will store who created request
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->unsignedBigInteger('notes')->nullable();
            $table->foreign('notes')->references('id')->on('request_notes');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('noti_mobile')->nullable();
            $table->string('noti_email')->nullable();
            $table->string('email')->nullable();
            $table->string('str_month')->nullable();
            $table->integer('int_year')->nullable();
            $table->integer('int_date')->nullable();
            $table->boolean('is_mobile')->nullable();

            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            
            $table->string('CommunicationType')->nullable();
            $table->string('RemindReservationCount')->nullable();
            $table->string('RemindHouseCallCount')->nullable();
            $table->string('IsSetFollowupSent')->nullable();
            $table->string('IsReservationReminderSent')->nullable();
            $table->string('Latitude')->nullable();
            $table->string('Longitude')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_client');
    }
};
