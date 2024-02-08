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
        Schema::create('health_professional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profession')->nullable();
            $table->foreign('profession')->references('id')->on('health_professional_type');
            $table->unsignedBigInteger('state')->nullable();
            $table->foreign('state')->references('id')->on('account_type');
            $table->string('vendor_name');
            $table->string('email')->nullable();
            $table->integer('business_contact')->nullable();
            $table->integer('fax_number');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('states')->nullable();
            $table->integer('zip_code')->nullable();
            $table->integer('mobile')->nullable()   ;

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_professional');
    }
};
