<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin Table
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');


            // $table->unsignedBigInteger('region_id')->nullable();
            // $table->foreign('region_id')->references('id')->on('regions');
            
            $table->unsignedBigInteger('status')->nullable();
            $table->foreign('status')->references('id')->on('status');


            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->integer('mobile')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('zip')->nullable();
            $table->integer('alt_phone')->nullable();

            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('user_roles');

            // $table->unsignedBigInteger('created_by');
            // $table->foreign('created_by')->references('users');
            // $table->unsignedBigInteger('modified_by');
            // $table->foreign('modified_by')->references('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
