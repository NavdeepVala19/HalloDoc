<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *  this table have 4 type of request
     * 1.patient
     * 2.family
     * 3.conceirege
     * 4.business
     */
    public function up(): void
    {
        Schema::create('request_type', function (Blueprint $table) {
            $table->id();
            $table->string('request_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_type');
    }
};
