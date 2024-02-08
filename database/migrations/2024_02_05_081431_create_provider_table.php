<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Provider/Physician detail
     */
    public function up(): void
    {
        Schema::create('provider', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('regions_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('regions_id')->references('id')->on('regions');
            $table->string('password')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->integer('mobile')->nullable();
            $table->integer('admin_notes')->nullable();
            $table->integer('medical_license')->nullable();
            $table->string('address_line_one')->nullable();
            $table->string('address_line_two')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('zipcode')->nullable();
            $table->string('business_name')->nullable();
            $table->string('website')->nullable();
            $table->string('image');
            $table->string('signature')->nullable();
            $table->string('agreement')->nullable();
            $table->string('hipaa_compliance')->nullable();

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
