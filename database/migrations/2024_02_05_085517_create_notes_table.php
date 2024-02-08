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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->nullable();
            $table->foreign('request_id')->references('id')->on('request');
            $table->string('patient_notes')->nullable();
            $table->string('transfer_notes')->nullable();
            $table->string('physician_notes')->nullable();
            $table->string('admin_notes')->nullable();
            $table->string('additional_notes')->nullable();
            $table->string('admin_cancellation_notes')->nullable();
            $table->string('patient_cancellation_notes')->nullable();
            $table->string('physician_cancellation_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
