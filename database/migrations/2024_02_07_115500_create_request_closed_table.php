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
        Schema::create('request_closed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->unsignedBigInteger('request_status_id');
            $table->foreign('request_status_id')->references('id')->on('request_status');

            // Checkout for these
            $table->unsignedBigInteger('phy_notes')->nullable();
            $table->foreign('phy_notes')->references('id')->on('request_notes');
            $table->unsignedBigInteger('client_notes')->nullable();
            $table->foreign('client_notes')->references('id')->on('request_notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_closed');
    }
};
