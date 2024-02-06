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
        Schema::create('provider', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->integer('mobile');
            $table->string('region_served');
            $table->integer('medical_license');
            $table->string('address_line_one');
            $table->string('address_line_two');
            $table->string('city');
            $table->string('state');
            $table->integer('zipcode');
            $table->string('business_name');
            $table->string('website');
            $table->string('image');
            $table->string('signature');
            $table->string('agreement');
            $table->string('hipaa_compliance');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider');
    }
};
