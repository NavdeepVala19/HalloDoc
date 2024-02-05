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
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->string('patient_notes');
            $table->string('transfer_notes');
            $table->string('physician_notes');
            $table->string('admin_notes');
            $table->string('additional_notes');
            $table->string('admin_cancellation_notes');
            $table->string('patient_cancellation_notes');
            $table->string('physician_cancellation_notes');
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
