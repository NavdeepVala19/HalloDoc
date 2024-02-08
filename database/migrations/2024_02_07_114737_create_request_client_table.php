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
     * create request page for admin/provider 
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
            $table->foreign('notes')->references('id')->on('notes');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->integer('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('notif_mobile')->nullable();
            $table->string('notif_email')->nullable();
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
