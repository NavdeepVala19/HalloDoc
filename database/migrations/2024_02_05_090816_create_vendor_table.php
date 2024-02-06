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
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('profession');
            $table->string('name');
            $table->string('contact');
            $table->string('email');
            $table->integer('fax_number');
            $table->integer('zipcode');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
