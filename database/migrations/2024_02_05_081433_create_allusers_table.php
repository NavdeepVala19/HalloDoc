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
        Schema::create('allusers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('status')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('account_type_id')->references('id')->on('account_type');
            $table->foreign('status')->references('id')->on('status');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('first_name');
            $table->string('user_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('password')->nullable();
            $table->integer('phone')->nullable();
            $table->integer('zipcode')->nullable();
            $table->integer('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('created_by');
            $table->string('modified_by')->nullable();

            // $table->boolean('is_mobile');
            // $table->boolean('is_request_with_email');
            // $table->string('str_month');
            // $table->integer('int_year');
            // $table->integer('int_date');
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
