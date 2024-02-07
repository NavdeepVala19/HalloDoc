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
            $table->unsignedBigInteger('profession');
            $table->foreign('profession')->references('id')->on('health_professional_type');
            $table->unsignedBigInteger('state');
            $table->foreign('state')->references('id')->on('account_type');
            $table->string('vendor_name');
            $table->string('email');
            $table->integer('business_contact');
            $table->integer('fax_number');
            $table->string('address');
            $table->string('city');
            $table->string('states');
            $table->integer('zip_code');
            $table->integer('mobile');

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
