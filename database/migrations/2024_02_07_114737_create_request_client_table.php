<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  These Table contains all the data related to request created by (Patient/family/Buisness partner/Concierge)
    public function up(): void
    {
        Schema::create('request_client', function (Blueprint $table) {
            $table->id();
            // will store who created request
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('mobile');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('notif_mobile');
            $table->string('notif_email');
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
