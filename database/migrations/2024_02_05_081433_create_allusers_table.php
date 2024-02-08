<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * All data related to users Patient/Admin/Physician.
     */
    public function up(): void
    {
        Schema::create('allusers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('region_id');
            $table->foreign('account_type_id')->references('id')->on('account_type');
            $table->foreign('status')->references('id')->on('status');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('first_name');
            $table->string('user_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->integer('phone');
            $table->integer('zipcode');
            $table->integer('street');
            $table->string('city');
            $table->string('state');
            $table->string('created_by');
            $table->string('modified_by');

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
