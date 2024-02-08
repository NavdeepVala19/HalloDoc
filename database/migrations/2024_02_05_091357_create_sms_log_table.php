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
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('request_id')->references('id')->on('request');
            $table->foreign('admin_id')->references('id')->on('roles');
            $table->foreign('provider_id')->references('id')->on('roles');
            $table->string('recepient_firstname');
            $table->string('recepient_lastname');
            $table->string('action');
            $table->integer('mobile');
            $table->date('sent_date');
            $table->boolean('sent');
            $table->integer('tries');
            $table->integer('confirmation_number');
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
