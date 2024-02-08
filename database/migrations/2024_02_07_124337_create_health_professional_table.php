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
            
            
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            
            // $table->unsignedBigInteger('state')->nullable();
            // $table->foreign('state')->references('id')->on('account_type');
            
            $table->string('vendor_name');
            $table->integer('fax_number');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('zip')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->string('email')->nullable();
            $table->string('business_contact')->nullable();

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
