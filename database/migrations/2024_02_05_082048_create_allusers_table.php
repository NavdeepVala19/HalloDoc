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
        Schema::create('allusers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type_id');
            $table->unsignedBigInteger('status');
            $table->foreign('account_type_id')->references('id')->on('account_type');
            $table->foreign('status')->references('id')->on('status');
            $table->string('first_name');
            $table->string('user_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->integer('phone');
            $table->integer('zipcode');
            $table->string('city');
            $table->string('state');
            $table->string('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allusers');
    }
};
