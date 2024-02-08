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
        Schema::create('physician_location', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('provider');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physician_location');
    }
};
