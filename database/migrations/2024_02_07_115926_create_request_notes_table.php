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
        Schema::create('request_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('request');
            $table->unsignedBigInteger('phy_notes');
            $table->foreign('phy_notes')->references('id')->on('notes');
            $table->unsignedBigInteger('admin_notes');
            $table->foreign('admin_notes')->references('id')->on('notes');

            $table->integer('date');
            $table->integer('month');
            $table->integer('year');
            

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_notes');
    }
};
