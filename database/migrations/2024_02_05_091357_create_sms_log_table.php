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
        Schema::create('sms_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('request_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('request_id')->references('id')->on('request');
            $table->foreign('admin_id')->references('id')->on('roles');
            $table->foreign('provider_id')->references('id')->on('roles');
            $table->string('recepient_firstname');
            $table->string('recepient_lastname')->nullable();
            $table->string('action')->nullable();
            $table->integer('mobile');
            $table->date('sent_date')->nullable();
            $table->boolean('sent')->nullable();
            $table->integer('tries')->nullable();
            $table->integer('confirmation_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_log');
    }
};
