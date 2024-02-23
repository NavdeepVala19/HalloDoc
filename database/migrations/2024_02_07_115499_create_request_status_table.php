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
        Schema::create('request_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->unsignedBigInteger('status');
            $table->foreign('status')->references('id')->on('status');
            $table->unsignedBigInteger('physician_id')->nullable();
            $table->foreign('physician_id')->references('id')->on('provider');
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admin');
            // $table->string('TransToPhysicianId');
            $table->unsignedBigInteger('TransToPhysicianId')->nullable();
            $table->foreign('TransToPhysicianId')->references('id')->on('provider');
            $table->text('notes')->nullable();
            $table->boolean('TransToAdmin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_status');
    }
};
