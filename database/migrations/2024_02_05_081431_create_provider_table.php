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
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('regions_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('regions_id')->references('id')->on('regions');
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->integer('mobile');
            $table->integer('admin_notes');
            $table->integer('medical_license');
            $table->string('address_line_one');
            $table->string('address_line_two');
            $table->string('city');
            $table->string('state');
            $table->integer('zipcode');
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
