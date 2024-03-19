<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * User Table
     */
    public function up(): void
    {
        Schema::create('allusers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->boolean('is_mobile')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->integer('zipcode')->nullable();

            $table->string('str_month')->nullable();
            $table->integer('int_year')->nullable();
            $table->integer('int_date')->nullable();

            // Check Here
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();


            $table->enum('status', ['pending', 'active', 'inactive'])->nullable();
            // $table->foreign('status')->references('id')->on('status');
            $table->boolean('is_request_with_email')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allusers');
    }
};
