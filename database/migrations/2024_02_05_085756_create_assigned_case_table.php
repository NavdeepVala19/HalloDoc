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
        Schema::create('assigned_case', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('account_type_id')->references('id')->on('account_type');
            $table->foreign('request_id')->references('id')->on('request');
            $table->foreign('provider_id')->references('id')->on('provider');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_case');
    }
};
